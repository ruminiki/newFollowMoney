<?php

namespace App\Http\Controllers;

use App\DataTables\BankAccountDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use App\Repositories\BankAccountRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;
use DB;
use App\Models\AccountStatement;
use Yajra\Datatables\Datatables;

class BankAccountController extends AppBaseController
{
    /** @var  BankAccountRepository */
    private $bankAccountRepository;

    public function __construct(BankAccountRepository $bankAccountRepo)
    {
        $this->bankAccountRepository = $bankAccountRepo;
    }

    /**
     * Display a listing of the BankAccount.
     *
     * @param BankAccountDataTable $bankAccountDataTable
     * @return Response
     */
    public function index(BankAccountDataTable $bankAccountDataTable)
    {
        return $bankAccountDataTable->render('bankAccounts.index');
    }

    /**
     * Show the form for creating a new BankAccount.
     *
     * @return Response
     */
    public function create()
    {
        return view('bankAccounts.create');
    }

    /**
     * Store a newly created BankAccount in storage.
     *
     * @param CreateBankAccountRequest $request
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
     * Display the specified BankAccount.
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

        return view('bankAccounts.show')->with('bankAccount', $bankAccount);
    }

    /**
     * Show the form for editing the specified BankAccount.
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

        return view('bankAccounts.edit')->with('bankAccount', $bankAccount);
    }

    /**
     * Update the specified BankAccount in storage.
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
     * Remove the specified BankAccount from storage.
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

    public function accountStatement($id){
        $bankAccount = $this->bankAccountRepository->findWithoutFail($id);

        if (empty($bankAccount)) {
            Flash::error('Bank Account not found');

            return redirect(route('bankAccounts.index'));
        }

        $bank_accounts = DB::table('bank_accounts')->get();
        $account_statements = array();
        foreach ($bank_accounts as $bank_account){
            $account_statement = new AccountStatement();
            $account_statement->date = '01/01/2000';
            $account_statement->movement = 1;
            $account_statement->type = 'DEBIT';
            $account_statement->value = 10;
            array_push($account_statements, $account_statement);
        }

        //$data_table = Datatables::of($account_statements)->make(true);
        
        return view('bankAccounts.account_statement')->with('account_statements', $account_statements)->with('bankAccount', $bank_account);
    }
}
