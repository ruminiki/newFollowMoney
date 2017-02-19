<?php

namespace App\DataTables;

use App\Models\Movement;
use Form;
use Yajra\Datatables\Services\DataTable;
use DB;

class MovementDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->of($this->query())
            ->addColumn('action', 'movements.datatables_actions')
            ->editColumn('emission_date', function ($category) {
                    return !empty($category->emission_date) ? $category->emission_date->format('d/m/Y') : '';
            })
            ->editColumn('maturity_date', function ($category) {
                    return !empty($category->maturity_date) ? $category->maturity_date->format('d/m/Y') : '';
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

        $movements = Movement::from( DB::raw('movements as movements') )
        ->leftJoin( DB::raw('categories as category'), DB::raw( 'category.id' ), '=', DB::raw( 'movements.category_id' ))
        ->leftJoin( DB::raw('bank_accounts as bankAccount'), DB::raw( 'bankAccount.id' ), '=', DB::raw( 'movements.bank_account_id' ))
        ->leftJoin( DB::raw('credit_cards as creditCard'), DB::raw( 'creditCard.id' ), '=', DB::raw( 'movements.credit_card_id' ))
        ->leftJoin( DB::raw('payment_forms as paymentForm'), DB::raw( 'paymentForm.id' ), '=', DB::raw( 'movements.payment_form_id' ))
        ->select(DB::raw('movements.*, category.description as category, bankAccount.description as bank_account, creditCard.description as credit_card, paymentForm.description as payment_form'))
        ->orderBy('movements.maturity_date', 'desc')
        ->get();

        //filtrar movimentos do mes

        return $this->applyScopes($movements);
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
            'Description' => ['name' => 'description', 'data' => 'description', 'width' => '30'],
            'Emission' => ['name' => 'emission_date', 'data' => 'emission_date', 'width' => '8%'],
            'Maturity' => ['name' => 'maturity_date', 'data' => 'maturity_date', 'width' => '8%'],
            'Operation' => ['name' => 'operation', 'data' => 'operation', 'width' => '8%'],
            'Value' => ['name' => 'value', 'data' => 'value', 'width' => '8%'],
            'Category' => ['name' => 'category', 'data' => 'category', 'width' => '18%'],
            'Bank Account' => ['name' => 'bank_account', 'data' => 'bank_account', 'width' => '18']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'movements';
    }
}

