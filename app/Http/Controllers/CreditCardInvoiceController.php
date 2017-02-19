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
        $creditCardInvoices = $this->creditCardInvoiceRepository->all();

        return view('creditCardInvoices.index')
            ->with('creditCardInvoices', $creditCardInvoices);
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

        return view('creditCardInvoices.show')->with('creditCardInvoice', $creditCardInvoice);
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
        $creditCardInvoice = $this->creditCardInvoiceRepository->findWithoutFail($id);

        if (empty($creditCardInvoice)) {
            Flash::error('Credit Card Invoice not found');

            return redirect(route('creditCardInvoices.index'));
        }

        $this->creditCardInvoiceRepository->delete($id);

        Flash::success('Credit Card Invoice deleted successfully.');

        return redirect(route('creditCardInvoices.index'));
    }
}
