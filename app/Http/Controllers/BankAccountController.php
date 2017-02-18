<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Repositories\BankAccountRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Auth;
use DB;

class BankAccountController extends AppBaseController
{
    /** @var  bank_accountRepository */
    private $bankAccountRepository;

    public function __construct(BankAccountRepository $bankAccountRepo)
    {
        $this->bankAccountRepository = $bankAccountRepo;
    }

    /**
     * Display a listing of the bank_account.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->bankAccountRepository->pushCriteria(new RequestCriteria($request));
        $bankAccounts = $this->bankAccountRepository->paginate(20);

        return view('bank_accounts.index')
            ->with('bankAccounts', $bankAccounts);
    }

    /**
     * Show the form for creating a new bank_account.
     *
     * @return Response
     */
    public function create()
    {
        return view('bank_accounts.create');
    }

    /**
     * Store a newly created bank_account in storage.
     *
     * @param Createbank_accountRequest $request
     *
     * @return Response
     */
    public function store(CreateBankAccountRequest $request)
    {
        $input = $request->all();
        
        $input['user_id'] = Auth::id();
        
        $bankAccount = $this->bankAccountRepository->create($input);

        Flash::success('Bank Account saved successfully.');

        return redirect(route('bankAccounts.index'));
    }

    /**
     * Display the specified bank_account.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bankAccount = $this->bankAccountRepository->findWithoutFail($id);

        if (empty($bankAccount)) {
            Flash::error('Bank Account not found');

            return redirect(route('bankAccounts.index'));
        }

        return view('bank_accounts.show')->with('bankAccount', $bankAccount);
    }

    /**
     * Show the form for editing the specified bank_account.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bankAccount = $this->bankAccountRepository->findWithoutFail($id);

        if (empty($bankAccount)) {
            Flash::error('Bank Account not found');

            return redirect(route('bankAccounts.index'));
        }

        return view('bank_accounts.edit')->with('bankAccount', $bankAccount);
    }

    /**
     * Update the specified bank_account in storage.
     *
     * @param  int              $id
     * @param UpdateBankAccountRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBankAccountRequest $request)
    {
        $bankAccount = $this->bankAccountRepository->findWithoutFail($id);

        if (empty($bankAccount)) {
            Flash::error('Bank Account not found');

            return redirect(route('bankAccounts.index'));
        }

        $bankAccount = $this->bankAccountRepository->update($request->all(), $id);

        Flash::success('Bank Account updated successfully.');

        return redirect(route('bankAccounts.index'));
    }

    /**
     * Remove the specified bank_account from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bankAccount = $this->bankAccountRepository->findWithoutFail($id);

        if (empty($bankAccount)) {
            Flash::error('Bank Account not found');

            return redirect(route('bankAccounts.index'));
        }

        $this->bankAccountRepository->delete($id);

        Flash::success('Bank Account deleted successfully.');

        return redirect(route('bankAccounts.index'));
    }

    public function search(Request $request)
    {
        // Gets the query string from our form submission 
        $query = $request['search'];
        // Returns an array of articles that have the query string located somewhere within 
        // our articles titles. Paginates them so we can break up lots of search results.
        $bank_accounts = DB::table('bank_accounts')->where('description', 'LIKE', '%' . $query . '%')->paginate(20);
            
        // returns a view and passes the view the list of articles and the original query.
        return view('bank_accounts.index', compact('bank_accounts', 'query'));

        //return redirect(route('categories.index'));
     }
}
