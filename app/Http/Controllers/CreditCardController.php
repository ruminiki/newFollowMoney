<?php

namespace App\Http\Controllers;

use App\DataTables\CreditCardDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCreditCardRequest;
use App\Http\Requests\UpdateCreditCardRequest;
use App\Repositories\CreditCardRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Models\CreditCard;
use App\Models\CreditCardInvoice;
use App\Models\Movement;
use Response;
use Auth;
use Session;
use Log;
use Request;
use View;

class CreditCardController extends AppBaseController
{
    /** @var  CreditCardRepository */
    private $creditCardRepository;

    public function __construct(CreditCardRepository $creditCardRepo)
    {
        $this->creditCardRepository = $creditCardRepo;
    }

    /**
     * Display a listing of the CreditCard.
     *
     * @param CreditCardDataTable $creditCardDataTable
     * @return Response
     */
    public function index(CreditCardDataTable $creditCardDataTable)
    {
        return $creditCardDataTable->render('creditCards.index');
    }

    /**
     * Show the form for creating a new CreditCard.
     *
     * @return Response
     */
    public function create()
    {
        return view('creditCards.create');
    }

    /**
     * Store a newly created CreditCard in storage.
     *
     * @param CreateCreditCardRequest $request
     *
     * @return Response
     */
    public function store(CreateCreditCardRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $input['limit'] = $this->formatLimit($input['limit']);
        $creditCard = $this->creditCardRepository->create($input);

        Flash::success('Credit Card saved successfully.');

        return redirect(route('creditCards.index'));
    }

    /**
     * Display the specified CreditCard.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $creditCard = $this->creditCardRepository->findWithoutFail($id);

        if (empty($creditCard)) {
            Flash::error('Credit Card not found');

            return redirect(route('creditCards.index'));
        }

        return view('creditCards.show')->with('creditCard', $creditCard);
    }

    /**
     * Show the form for editing the specified CreditCard.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $creditCard = $this->creditCardRepository->findWithoutFail($id);

        if (empty($creditCard)) {
            Flash::error('Credit Card not found');

            return redirect(route('creditCards.index'));
        }

        return view('creditCards.edit')->with('creditCard', $creditCard);
    }

    /**
     * Update the specified CreditCard in storage.
     *
     * @param  int              $id
     * @param UpdateCreditCardRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCreditCardRequest $request)
    {
        $creditCard = $this->creditCardRepository->findWithoutFail($id);
        if (empty($creditCard)) {
            Flash::error('Credit Card not found');

            return redirect(route('creditCards.index'));
        }

        /*$request['limit'] = preg_replace('/[^0-9,]/s', '', $request['limit']);
        $request['limit'] = replace(',', '.', $request['limit']);*/

        $request['limit'] = $this->formatLimit($request['limit']);
        $creditCard = $this->creditCardRepository->update($request->all(), $id);

        Flash::success('Credit Card updated successfully.');

        return redirect(route('creditCards.index'));
    }

    /**
     * Remove the specified CreditCard from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $creditCard = $this->creditCardRepository->findWithoutFail($id);

        if (empty($creditCard)) {
            Flash::error('Credit Card not found');

            return redirect(route('creditCards.index'));
        }

        //TODO: não pode remover se tiver alguma fatura em aberto

        $this->creditCardRepository->delete($id);

        Flash::success('Credit Card deleted successfully.');

        return redirect(route('creditCards.index'));
    }

    private function formatLimit($limit){
        $limit = preg_replace('/[^0-9,]/s', '', $limit);
        $limit = str_replace(',', '.', $limit);
        if ( strpos($limit, '.') == FALSE ){
            $limit = substr($limit,0,-2) . '.' . substr($limit,-2);
        }
        return $limit;
    }

    public function invoices($id, $year){
        Session::put('year_reference', $year);
        $credit_card  = CreditCard::whereRaw('id = ? and user_id = ?', [$id, Auth::id()])->first();

        if (empty($credit_card)) {
            $credit_card = CreditCard::whereRaw('user_id = ?', Auth::id())->first();
        }

        Session::put('selected_credit_card_id', $id);

        //====LOAD INVOICES
        $invoices = CreditCardInvoice::whereRaw('credit_card_id = ? and user_id = ? and reference_year = ?', 
                            [$id, Auth::id(), $year])->with('movements')->get();


        foreach ($invoices as $invoice) {
            $value = 0;
            $movements = $invoice->movements;

            foreach ($movements as $movement) {
                if ( $movement->isCredit() ){
                    $value -= $movement->value;
                }else{
                    $value += $movement->value;
                }
            }
            $invoice->value = $value;
            Log::info('Invoice Credit Card: ' . $credit_card->description . ' Year: ' . Session::get('year_reference') . ' Value: ' . $value);
        }

        if ( Request::ajax() ){
            $view = View::make('creditCardInvoices.table',compact('invoices'))->render();
            return Response::json(array('html' => $view));
        }
        return View::make('creditCardInvoices.index',compact('credit_card', 'invoices'))->render();
    }
}
