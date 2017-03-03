<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCreditCardInvoiceRequest;
use App\Http\Requests\UpdateCreditCardInvoiceRequest;
use App\Repositories\CreditCardInvoiceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\CreditCard;
use App\Models\CreditCardInvoice;
use Auth;
use Session;
use Request as _Request;
use View;

class CreditCardInvoiceController extends AppBaseController
{
    /** @var  CreditCardInvoiceRepository */
    private $creditCardInvoiceRepository;

    public function __construct(CreditCardInvoiceRepository $creditCardInvoiceRepo)
    {
        $this->creditCardInvoiceRepository = $creditCardInvoiceRepo;
    }

    /**
     * Display a listing of the CreditCardInvoice.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->creditCardInvoiceRepository->pushCriteria(new RequestCriteria($request));
        $invoices = $this->creditCardInvoiceRepository->all();
        $credit_cards = CreditCard::orderBy('description', 'asc')->whereRaw('user_id = ?', [Auth::id()])->pluck('description', 'id');

        $min_year = date('Y');
        $max_year = date('Y') + 1;

        foreach ($invoices as $invoice) {
            if ( $invoice->reference_year < $min_year ){
                $min_year = $invoice->reference_year;
            }
        }

        return view('creditCardInvoices.index')
            ->with('invoices', array())
            ->with('credit_cards', $credit_cards)
            ->with('min_year', $min_year)
            ->with('max_year', $max_year);

    }

    /**
     * Show the form for creating a new CreditCardInvoice.
     *
     * @return Response
     */
    public function create()
    {
        return view('creditCardInvoices.create');
    }

    /**
     * Store a newly created CreditCardInvoice in storage.
     *
     * @param CreateCreditCardInvoiceRequest $request
     *
     * @return Response
     */
    public function store(CreateCreditCardInvoiceRequest $request)
    {
        $input = $request->all();

        $creditCardInvoice = $this->creditCardInvoiceRepository->create($input);

        Flash::success('Credit Card Invoice saved successfully.');

        return redirect(route('creditCardInvoices.index'));
    }

    /**
     * Display the specified CreditCardInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $creditCardInvoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($creditCardInvoice)) {
            Flash::error('Credit Card Invoice not found');

            return redirect(route('creditCardInvoices.index'));
        }

        return view('creditCardInvoices.show')
               ->with('creditCardInvoice', $creditCardInvoice)
               ->with('credits', 0)
               ->with('debits', 0);
    }

    /**
     * Show the form for editing the specified CreditCardInvoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $creditCardInvoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($creditCardInvoice)) {
            Flash::error('Credit Card Invoice not found');

            return redirect(route('creditCardInvoices.index'));
        }

        return view('creditCardInvoices.edit')->with('creditCardInvoice', $creditCardInvoice);
    }

    /**
     * Update the specified CreditCardInvoice in storage.
     *
     * @param  int              $id
     * @param UpdateCreditCardInvoiceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCreditCardInvoiceRequest $request)
    {
        $creditCardInvoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($creditCardInvoice)) {
            Flash::error('Credit Card Invoice not found');

            return redirect(route('creditCardInvoices.index'));
        }

        $creditCardInvoice = $this->creditCardInvoiceRepository->update($request->all(), $id);

        Flash::success('Credit Card Invoice updated successfully.');

        return redirect(route('creditCardInvoices.index'));
    }

    /**
     * Remove the specified CreditCardInvoice from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $invoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('Credit Card Invoice not found');

            return redirect(route('creditCardInvoices.index'));
        }

        if ( count($invoice->movements) > 0 ){
            Flash::warning('Cannot delete invoice with movements associateds. Pleas remove movements from invoice and try again!');
        }else{
            $this->creditCardInvoiceRepository->delete($id);
            Flash::success('Credit Card Invoice deleted successfully.');
        }

        return redirect(route('creditCardInvoices.index'));
    }

    public function pay($id)
    {
        $invoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('Credit Card Invoice not found');
            return redirect(route('creditCardInvoices.index'));
        }
        $value = 0;
        foreach ($invoice->movements as $movement) {
            if ( $movement->isCredit() ){
                $value -= $movement->value;
            }else{
                $value += $movement->value;
            }
        }

        $invoice->value = $value;
        $invoice->amount_paid = $value;
        $invoice->close();

        $invoice->save();

        Flash::success('Successful payment!');

        return redirect(route('creditCards.invoices', ['id'=>$invoice->credit_card_id, 'year'=>Session::get('year_reference')]));

    }

    public function unpay($id)
    {
        $invoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($invoice)) {
            Flash::error('Credit Card Invoice not found');
            return redirect(route('creditCardInvoices.index'));
        }
        
        $invoice->reopen();
        $invoice->save();

        Flash::success('Successful unpayment!');

        return redirect(route('creditCards.invoices', ['id'=>$invoice->credit_card_id, 'year'=>Session::get('year_reference')]));

    }
}
