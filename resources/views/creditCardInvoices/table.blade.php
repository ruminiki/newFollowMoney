<div id="container">
    {{ Form::hidden('year_reference', Session::get('year_reference'), ['id'=>'year_reference'] ) }}
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-bordered" id="account-statements-table">
                    <tr>
                        <td colspan="6">{{ 'Previous Balance: R$ '. number_format(0, 2, ',', '.')  }}</td>
                    </tr>
                    <tr>
                        <th>Reference</th>
                        <th>Expiration</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Paid</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->reference_month . '/' . $invoice->reference_year }}</td>
                            <td>{{ date_format($invoice->maturity_date,"d-m-Y") }}</td>
                            <td>{{ $invoice->status }}</td>
                            <td>{{ 'R$ '. number_format($invoice->value, 2, ',', '.') }}</td>
                            <td>{{ 'R$ '. number_format($invoice->amount_paid, 2, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" style="width:130px;">
                                    {!! Form::open(['action' => ['CreditCardInvoiceController@destroy', $invoice->id], 'method' => 'delete', 'onsubmit' => 'return confirmDelete()']) !!}
                                    {{ link_to_route('creditCardInvoices.show', 'View', $invoice->id, ['class' => 'btn btn-default   btn-xs']) }}
                                    {{ Form::button('Pay',['id'=> 'btn_pay', 'class' => 'btn btn-default btn-xs'] ) }}
                                    {{ Form::button('Unpay',['id'=> 'btn_unpay', 'class' => 'btn btn-default btn-xs'] ) }}
                                    {!! Form::submit('Delete',['class'=>'btn btn-danger btn-xs']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6">{{ 'Credits: R$ '. number_format(0, 2, ',', '.') . ' ' . 'Debits: R$ ' . number_format(0, 2, ',', '.') . ' ' . 'Balance: R$ ' . number_format((0 + 0 - 0), 2, ',', '.')  }}</td>
                    </tr>
                </table>
                
                <br>

                <section class="content-footer">
                    <div class="dt-buttons btn-group" style="margin-left:0px; margin-bottom:10px">
                        <a class="btn btn-default" href="{!! route('bankAccounts.index') !!}">Voltar</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>