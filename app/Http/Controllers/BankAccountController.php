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
use App\Models\BankAccount;
use Yajra\Datatables\Datatables;
use Session;
use Carbon\Carbon;
use Log;
use View;
use Request;

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

    public function account_statement($id, $month){
        Session::put('month_reference', $month);
        $bank_account  = $this->bankAccountRepository->findWithoutFail($id);
        $bank_accounts = BankAccount::orderBy('description', 'asc')->whereRaw('user_id = ?', [Auth::id()])->pluck('description', 'id');

        if (empty($bank_account)) {
            $bank_account = BankAccount::whereRaw('user_id = ?', Auth::id())->first();
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

        Log::info('Bank Account: ' . $bank_account->description);
        Log::info('Month: ' . date('M', mktime(0, 0, 0, $month, 10)));
        Log::info('Previous Credit: ' . $previous_credit . '  -  ' . 'Previous Debit: ' . $previous_debit);
        Log::info('Previous Balance: ' . $previous_balance);


        /*$view = View::make('bankAccounts.account_statement')
               ->with('movements', $movements)
               ->with('bank_account', $bank_account)
               ->with('bank_accounts', $bank_accounts)
               ->with('credits', $credits)
               ->with('debits', $debits)
               ->with('previous_balance', $previous_balance);*/


        if ( Request::ajax() ){
            $view = View::make('bankAccounts.account_statement_content',
                compact('movements', 'bank_account', 'credits', 'debits', 'previous_balance'))->render();
            return Response::json(array('html' => $view));
            /*$sections = $view->renderSections(); 
            return json_encode($sections); */
        }

        return View::make('bankAccounts.account_statement',
                compact('movements', 'bank_account', 'bank_accounts', 'credits', 'debits', 'previous_balance'))->render();

        //return $view;
    }

}
