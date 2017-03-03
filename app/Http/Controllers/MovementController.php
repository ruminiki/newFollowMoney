<?php

namespace App\Http\Controllers;

use App\DataTables\MovementDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMovementRequest;
use App\Http\Requests\UpdateMovementRequest;
use App\Repositories\MovementRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;
use App\Models\Category;
use App\Models\PaymentForm;
use App\Models\CreditCard;
use App\Models\BankAccount;
use App\Models\Movement;
use Log;
use Redirect;
use Session;
use Exception;
use Carbon\Carbon;

class MovementController extends AppBaseController
{
    
    /** @var  MovementRepository */
    private $movementRepository;

    public function __construct(MovementRepository $movementRepo)
    {
        $this->movementRepository = $movementRepo;
        Session::put('month_reference', date('m'));
        Session::put('year_reference', date('Y'));
    }

    /**
     * Display a listing of the Movement.
     *
     * @param MovementDataTable $movementDataTable
     * @return Response
     */
    public function index(MovementDataTable $movementDataTable)
    {
        $credit_cards = CreditCard::orderBy('description', 'asc')->pluck('description', 'id');
        $categories = Category::orderBy('description', 'asc')->pluck('description', 'id');
        $bank_accounts = BankAccount::orderBy('description', 'asc')->pluck('description', 'id');
        return $movementDataTable->render('movements.index', 
                                         ['credit_cards'=>$credit_cards,
                                          'bank_accounts'=>$bank_accounts,
                                          'categories'=>$categories,
                                          'previous_balance'=>$this->calculePreviousBalance(),
                                          'month_balance'=>$this->calculeMonthBalance()]);
    }

    /**
     * Show the form for creating a new Movement.
     *
     * @return Response
     */
    public function create()
    {

        $categories = Category::orderBy('description', 'asc')->pluck('description', 'id');
        $paymentForms = PaymentForm::orderBy('description', 'asc')->pluck('description', 'id');
        $bankAccounts = BankAccount::orderBy('description', 'asc')->pluck('description', 'id');
        $creditCards = CreditCard::orderBy('description', 'asc')->pluck('description', 'id');
        return view('movements.create')
                    ->with('categories', $categories)
                    ->with('paymentForms', $paymentForms)
                    ->with('bankAccounts', $bankAccounts)
                    ->with('creditCards', $creditCards);

    }

    /**
     * Store a newly created Movement in storage.
     *
     * @param CreateMovementRequest $request
     *
     * @return Response
     */
    public function store(CreateMovementRequest $request)
    {
        $movement = new Movement($request->all());

        $movement->user_id = Auth::id(); 
        $movement->value = $this->formatValue($movement->value);

        ///===========CRIAR TRANSAÇÃO============

        //se o movimento for de cartão de crédito gerenciar a fatura
        if ( $movement->paymentForm <> null && $movement->paymentForm->generate_invoice ){
            if ( empty($movement->creditCard )){
                return Redirect::back()->withErrors(['For payment form ' . $movement->paymentForm->description . ', credit card is required.'])->withInput();
            }else{
                Log::info("====Add movement to invoice======");
                try {
                    $movement->creditCard->addToInvoice($movement);
                }catch(Exception $e) {
                    return Redirect::back()->withErrors([$e->getMessage()])->withInput();   
                }
            }
        }

        $movement = $this->movementRepository->create($movement->toArray());

        Flash::success('Movement saved successfully.');

        return redirect(route('movements.index'));
    }

    /**
     * Display the specified Movement.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $movement = $this->movementRepository->findWithoutFail($id);

        if (empty($movement)) {
            Flash::error('Movement not found');

            return redirect(route('movements.index'));
        }

        return view('movements.show')->with('movement', $movement);
    }

    /**
     * Show the form for editing the specified Movement.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $movement = $this->movementRepository->findWithoutFail($id);
        $categories = Category::orderBy('description', 'asc')->pluck('description', 'id');
        $paymentForms = PaymentForm::orderBy('description', 'asc')->pluck('description', 'id');
        $bankAccounts = BankAccount::orderBy('description', 'asc')->pluck('description', 'id');
        $creditCards = CreditCard::orderBy('description', 'asc')->pluck('description', 'id');

        if (empty($movement)) {
            Flash::error('Movement not found');

            return redirect(route('movements.index'));
        }

        return view('movements.edit')
                    ->with('movement', $movement)
                    ->with('categories', $categories)
                    ->with('paymentForms', $paymentForms)
                    ->with('bankAccounts', $bankAccounts)
                    ->with('creditCards', $creditCards);

    }

    /**
     * Update the specified Movement in storage.
     *
     * @param  int              $id
     * @param UpdateMovementRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMovementRequest $request)
    {
        $movement_old = $this->movementRepository->findWithoutFail($id)->with('creditCardInvoice')->first();

        if (empty($movement_old)) {
            Flash::error('Movement not found');
            return redirect(route('movements.index'));
        }

        //=====RECUPERA O VALOR E A DATA DE VENCIMENTO ANTERIOR
        $previous_value = $movement_old->value;
        $previous_maturity_date = $movement_old->maturity_date;
        $previous_credit_card = $movement_old->credit_card;

        //=====GERA NOVO OBJETO A PARTIR DA REQUISIÇÃO
        $movement = new Movement($request->all());
        $movement->user_id = Auth::id(); 
        $movement->value = $this->formatValue($movement->value);

        //====VERIFY IF HAS CREDIT CARD INVOICE AND IF THIS IS CLOSED
        //====ON THIS CASE VERIFY IF THE VALUE, CREDIT CARD AND DATE MATURITY IS CHANCHED
        try{
            if ( $movement_old->creditCardInvoice != null && $movement_old->credit_card_invoice_id > 0 ){
                if ( $movement_old->creditCardInvoice->isClosed() ){
                    if ( $movement_old->creditCard != null && $movement_old->credit_card_id > 0 ){
                        if ( $movement_old->credit_card_id != $movement->credit_card_id ){
                            throw new Exception("Error update movement. Invoice is closed");
                        }
                        if ( $movement_old->operation != $movement->operation ){
                            throw new Exception("Error update movement. Invoice is closed");
                        }
                        if ( $movement_old->status != $movement->status ){
                            throw new Exception("Error update movement. Invoice is closed");
                        }            
                        if ( $movement_old->bank_account_id != $movement->bank_account_id ){
                            throw new Exception("Error update movement. Invoice is closed");
                        }            
                        if ( $movement_old->value != $movement->value ){
                            throw new Exception("Error update movement. Invoice is closed");      
                        }
                        if ( date_format($movement_old->maturity_date, 'Ymd') != date_format($movement->maturity_date, 'Ymd') ) {
                            throw new Exception("Error update movement. Invoice is closed");        
                        }
                    }
                }
            }
        }catch(Exception $e) {
            return Redirect::back()->withErrors('This movement is on an enclosed invoice. So it can not be changed. Please reopen invoice to delete it')->withInput();    
        }
        
        $movement = $this->movementRepository->update($movement->toArray(), $id);
        Flash::success('Movement updated successfully.');
        return redirect(route('movements.index'));
    }

    /**
     * Remove the specified Movement from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $movement = $this->movementRepository->findWithoutFail($id)->with('creditCardInvoice')->first();

        if (empty($movement)) {
            Flash::error('Movement not found');
            return redirect(route('movements.index'));
        }

        //DO NOT DESTROY IF MOVEMENT IN A INVOICE CLOSED
        if ( $movement->credit_card_invoice_id > 0 ){
            if ( $movement->creditCardInvoice->isClosed() ){
                Flash::warning('This movement is on an enclosed invoice. So it can not be deleted. Please reopen invoice to delete it.');
                return Redirect::back();        
            }
        }  

        $this->movementRepository->delete($id);
        Flash::success('Movement deleted successfully.');
        return Redirect::back();
    }

    public function next_month(){
        $m = Session::get('month_reference');
        $y = Session::get('year_reference');
        if ( $m == 12 ){
            $m = 01;
            $y += 1;
        }else{
            $m += 1;
        }
        Session::put('month_reference', $m);
        Session::put('year_reference', $y);

        return redirect(route('movements.index'));
    }

    public function previous_month(){
        $m = Session::get('month_reference');
        $y = Session::get('year_reference');
        if ( $m == 01 ){
            $m = 12;
            $y -= 1;
        }else{
            $m -= 1;
        }
        Session::put('month_reference', $m);
        Session::put('year_reference', $y);
        
        return redirect(route('movements.index'));
    }

    private function formatValue($value){
        $value = preg_replace('/[^0-9,]/s', '', $value);
        $value = str_replace(',', '.', $value);
        if ( strpos($value, '.') == FALSE ){
            $value = substr($value,0,-2) . '.' . substr($value,-2);
        }
        return $value;
    }

    private function calculePreviousBalance(){
        //====PREVIOUS BALANCE
        $previous_credit = Movement::whereRaw('user_id = ? and movements.maturity_date < ? and operation = ?', 
                                            [Auth::id(), 
                                            Carbon::createFromDate(Session::get('year_reference'), Session::get('month_reference'), 01), 
                                            Movement::CREDIT])->sum('value');

        $previous_debit = Movement::whereRaw('user_id = ? and movements.maturity_date < ? and operation = ?', 
                                            [Auth::id(), 
                                            Carbon::createFromDate(Session::get('year_reference'), Session::get('month_reference'), 01), 
                                            Movement::DEBIT])->sum('value');

        return $previous_credit - $previous_debit;
    }

    private function calculeMonthBalance(){
        $credit = Movement::whereRaw('user_id = ? and operation = ? and MONTH(movements.maturity_date) = ? and YEAR(movements.maturity_date) = ?', 
                                    [Auth::id(), Movement::CREDIT,
                                    Session::get('month_reference'), Session::get('year_reference')])->sum('value');

        $debit = Movement::whereRaw('user_id = ? and operation = ? and MONTH(movements.maturity_date) = ? and YEAR(movements.maturity_date) = ?', 
                                    [Auth::id(), Movement::DEBIT,
                                    Session::get('month_reference'), Session::get('year_reference')])->sum('value');

        return $credit - $debit;
    }
}
