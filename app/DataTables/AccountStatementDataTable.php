<?php

namespace App\DataTables;

use App\Models\AccountStatement;
use Form;
use Yajra\Datatables\Services\DataTable;
use DB;

class AccountStatementDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->of($this->query())->make(true);

    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $bank_accounts = DB::table('bank_accounts')->get();
        $account_statements = array();
        foreach ($bank_accounts as $bank_account){
            $account_statement = new AccountStatement();
            $account_statement->date = now();
            $account_statement->movement = 1;
            $account_statement->type = 'DEBIT';
            $account_statement->value = 10;
            array_push($account_statements, $account_statement);
        }

        return $this->applyScopes($account_statements);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'columnDefs' => [
                    'defaultContent' => '-',
                    'targets' => '_all'
                ],
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'date' => ['name' => 'date', 'data' => 'date'],
            'movement' => ['name' => 'movement', 'data' => 'movement'],
            'type' => ['name' => 'type', 'data' => 'type'],
            'value' => ['name' => 'value', 'data' => 'value']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'accountStatements';
    }
}
