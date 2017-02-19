<?php

namespace App\DataTables;

use App\Models\CreditCard;
use Form;
use Yajra\Datatables\Services\DataTable;

class CreditCardDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'creditCards.datatables_actions')
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
        $creditCards = CreditCard::query();

        return $this->applyScopes($creditCards);
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
            'limit' => ['name' => 'limit', 'data' => 'limit'],
            'invoice_day' => ['name' => 'invoice_day', 'data' => 'invoice_day'],
            'closing_day' => ['name' => 'closing_day', 'data' => 'closing_day'],
            'created_at' => ['name' => 'created_at', 'data' => 'created_at'],
            'updated_at' => ['name' => 'updated_at', 'data' => 'updated_at']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'creditCards';
    }
}
