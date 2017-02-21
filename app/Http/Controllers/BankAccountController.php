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
use App\Models\Movement;
use Yajra\Datatables\Datatables;
use Session;
use Carbon\Carbon;
use Log;

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

    public function account_statement($id){
        $bank_account = $this->bankAccountRepository->findWithoutFail($id);

        if (empty($bank_account)) {
            Flash::error('Bank Account not found');
            return redirect(route('bankAccounts.index'));
        }

        //====LOAD MOVEMENTS
        $movements = Movement::whereRaw('bank_account_id = ? and ' . 
                            'user_id = ? and MONTH(movements.maturity_date) = ? and ' .
                            'YEAR(movements.maturity_date) = ?', 
                            [$id, Auth::id(), Session::get('month_reference'), Session::get('year_reference')])->get();

        $debits = 0;
        $credits = 0;
        foreach ($movements as $movement) {
            if ( $movement->isCredit() ){
                $credits += $movement->value;
            }else{
                $debits += $movement->value;
            }
        }

        //====PREVIOUS BALANCE
        $previous_credit = Movement::whereRaw('bank_account_id = ? and user_id = ? and movements.maturity_date < ? and operation = ?', 
                                             [$id, 
                                             Auth::id(), 
                                             Carbon::createFromDate(Session::get('year_reference'), Session::get('month_reference'), 01), 
                                             Movement::CREDIT])->sum('value');

        $previous_debit = Movement::whereRaw('bank_account_id = ? and user_id = ? and movements.maturity_date < ? and operation = ?', 
                                            [$id, 
                                            Auth::id(), 
                                            Carbon::createFromDate(Session::get('year_reference'), Session::get('month_reference'), 01), 
                                            Movement::DEBIT])->sum('value');

        $previous_balance = $previous_credit - $previous_debit;

        Log::info('Previous Credit: ' . $previous_credit . '  -  ' . 'Previous Debit: ' . $previous_debit);
        Log::info('Previous Balance: ' . $previous_balance);

        return view('bankAccounts.account_statement')
               ->with('movements', $movements)
               ->with('bank_account', $bank_account)
               ->with('credits', $credits)
               ->with('debits', $debits)
               ->with('previous_balance', $previous_balance);
    }

    public function next_month($bank_account_id){
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

        return redirect(route('bankAccounts.account_statement', $bank_account_id));
    }

    public function previous_month($bank_account_id){
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
        
        return redirect(route('bankAccounts.account_statement', $bank_account_id));
    }
}
