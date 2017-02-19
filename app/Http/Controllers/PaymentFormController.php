<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentFormDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePaymentFormRequest;
use App\Http\Requests\UpdatePaymentFormRequest;
use App\Repositories\PaymentFormRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;

class PaymentFormController extends AppBaseController
{
    /** @var  PaymentFormRepository */
    private $paymentFormRepository;

    public function __construct(PaymentFormRepository $paymentFormRepo)
    {
        $this->paymentFormRepository = $paymentFormRepo;
    }

    /**
     * Display a listing of the PaymentForm.
     *
     * @param PaymentFormDataTable $paymentFormDataTable
     * @return Response
     */
    public function index(PaymentFormDataTable $paymentFormDataTable)
    {
        return $paymentFormDataTable->render('paymentForms.index');
    }

    /**
     * Show the form for creating a new PaymentForm.
     *
     * @return Response
     */
    public function create()
    {
        return view('paymentForms.create');
    }

    /**
     * Store a newly created PaymentForm in storage.
     *
     * @param CreatePaymentFormRequest $request
     *
     * @return Response
     */
    public function store(CreatePaymentFormRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $paymentForm = $this->paymentFormRepository->create($input);

        Flash::success('Payment Form saved successfully.');

        return redirect(route('paymentForms.index'));
    }

    /**
     * Display the specified PaymentForm.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paymentForm = $this->paymentFormRepository->findWithoutFail($id);

        if (empty($paymentForm)) {
            Flash::error('Payment Form not found');

            return redirect(route('paymentForms.index'));
        }

        return view('paymentForms.show')->with('paymentForm', $paymentForm);
    }

    /**
     * Show the form for editing the specified PaymentForm.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paymentForm = $this->paymentFormRepository->findWithoutFail($id);

        if (empty($paymentForm)) {
            Flash::error('Payment Form not found');

            return redirect(route('paymentForms.index'));
        }

        return view('paymentForms.edit')->with('paymentForm', $paymentForm);
    }

    /**
     * Update the specified PaymentForm in storage.
     *
     * @param  int              $id
     * @param UpdatePaymentFormRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaymentFormRequest $request)
    {
        $paymentForm = $this->paymentFormRepository->findWithoutFail($id);

        if (empty($paymentForm)) {
            Flash::error('Payment Form not found');

            return redirect(route('paymentForms.index'));
        }

        $paymentForm = $this->paymentFormRepository->update($request->all(), $id);

        Flash::success('Payment Form updated successfully.');

        return redirect(route('paymentForms.index'));
    }

    /**
     * Remove the specified PaymentForm from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paymentForm = $this->paymentFormRepository->findWithoutFail($id);

        if (empty($paymentForm)) {
            Flash::error('Payment Form not found');

            return redirect(route('paymentForms.index'));
        }

        $this->paymentFormRepository->delete($id);

        Flash::success('Payment Form deleted successfully.');

        return redirect(route('paymentForms.index'));
    }
}
