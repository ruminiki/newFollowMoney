<?php

namespace App\DataTables;

use App\Models\BankAccount;
use Form;
use Yajra\Datatables\Services\DataTable;

class BankAccountDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'bankAccounts.datatables_actions')
             ->editColumn('updated_at', function ($category) {
                return !empty($category->updated_at) ? $category->updated_at->format('d/m/Y H:m') : '';
            })
            ->editColumn('created_at', function ($category) {
                    return !empty($category->created_at) ? $category->created_at->format('d/m/Y H:m') : '';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $bankAccounts = BankAccount::query();

        return $this->applyScopes($bankAccounts);
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
            'description' => ['name' => 'description', 'data' => 'description'],
            'number' => ['name' => 'number', 'data' => 'number'],
            'created_at' => ['name' => 'created_at', 'data' => 'created_at'],
            'updated_at' => ['name' => 'updated_at', 'data' => 'updated_at'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'bankAccounts';
    }
}
