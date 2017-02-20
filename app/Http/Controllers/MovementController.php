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

class MovementController extends AppBaseController
{
    /** @var  MovementRepository */
    private $movementRepository;

    public function __construct(MovementRepository $movementRepo)
    {
        $this->movementRepository = $movementRepo;
    }

    /**
     * Display a listing of the Movement.
     *
     * @param MovementDataTable $movementDataTable
     * @return Response
     */
    public function index(MovementDataTable $movementDataTable)
    {
        return $movementDataTable->render('movements.index');
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
        $movement = $this->movementRepository->findWithoutFail($id);

        if (empty($movement)) {
            Flash::error('Movement not found');

            return redirect(route('movements.index'));
        }

        $request['value'] = $this->formatValue($request['value']);
        $movement = $this->movementRepository->update($request->all(), $id);

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
        $movement = $this->movementRepository->findWithoutFail($id);

        if (empty($movement)) {
            Flash::error('Movement not found');

            return redirect(route('movements.index'));
        }

        $this->movementRepository->delete($id);

        Flash::success('Movement deleted successfully.');

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
}
