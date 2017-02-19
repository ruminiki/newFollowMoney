<?php

namespace App\Http\Controllers;

use App\DataTables\CreditCardDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCreditCardRequest;
use App\Http\Requests\UpdateCreditCardRequest;
use App\Repositories\CreditCardRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;

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

        $this->creditCardRepository->delete($id);

        Flash::success('Credit Card deleted successfully.');

        return redirect(route('creditCards.index'));
    }

    private function formatLimit($limit){

        $limit = preg_replace('/[^0-9,]/s', '', $limit);
        $limit = str_replace(',', '.', $limit);

        return $limit;
    }
}
