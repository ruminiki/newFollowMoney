<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCreditCardAPIRequest;
use App\Http\Requests\API\UpdateCreditCardAPIRequest;
use App\Models\CreditCard;
use App\Repositories\CreditCardRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CreditCardController
 * @package App\Http\Controllers\API
 */

class CreditCardAPIController extends AppBaseController
{
    /** @var  CreditCardRepository */
    private $creditCardRepository;

    public function __construct(CreditCardRepository $creditCardRepo)
    {
        $this->creditCardRepository = $creditCardRepo;
    }

    /**
     * Display a listing of the CreditCard.
     * GET|HEAD /creditCards
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->creditCardRepository->pushCriteria(new RequestCriteria($request));
        $this->creditCardRepository->pushCriteria(new LimitOffsetCriteria($request));
        $creditCards = $this->creditCardRepository->all();

        return $this->sendResponse($creditCards->toArray(), 'Credit Cards retrieved successfully');
    }

    /**
     * Store a newly created CreditCard in storage.
     * POST /creditCards
     *
     * @param CreateCreditCardAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCreditCardAPIRequest $request)
    {
        $input = $request->all();

        $creditCards = $this->creditCardRepository->create($input);

        return $this->sendResponse($creditCards->toArray(), 'Credit Card saved successfully');
    }

    /**
     * Display the specified CreditCard.
     * GET|HEAD /creditCards/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CreditCard $creditCard */
        $creditCard = $this->creditCardRepository->findWithoutFail($id);

        if (empty($creditCard)) {
            return $this->sendError('Credit Card not found');
        }

        return $this->sendResponse($creditCard->toArray(), 'Credit Card retrieved successfully');
    }

    /**
     * Update the specified CreditCard in storage.
     * PUT/PATCH /creditCards/{id}
     *
     * @param  int $id
     * @param UpdateCreditCardAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCreditCardAPIRequest $request)
    {
        $input = $request->all();

        /** @var CreditCard $creditCard */
        $creditCard = $this->creditCardRepository->findWithoutFail($id);

        if (empty($creditCard)) {
            return $this->sendError('Credit Card not found');
        }

        $creditCard = $this->creditCardRepository->update($input, $id);

        return $this->sendResponse($creditCard->toArray(), 'CreditCard updated successfully');
    }

    /**
     * Remove the specified CreditCard from storage.
     * DELETE /creditCards/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CreditCard $creditCard */
        $creditCard = $this->creditCardRepository->findWithoutFail($id);

        if (empty($creditCard)) {
            return $this->sendError('Credit Card not found');
        }

        $creditCard->delete();

        return $this->sendResponse($id, 'Credit Card deleted successfully');
    }
}
