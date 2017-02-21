@extends('layouts.app')

@push('scripts')
    <script type="text/javascript">
        function confirmDelete() {
            var result = confirm('Are you sure you want to delete?');
            if (result) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@endpush

@section('content')
    <section class="content-header">
        <h3 class="pull-left">{!! $bank_account->description . ' - ' . $bank_account->number . ' - ' . date('F', mktime(0, 0, 0, Session::get('month_reference'), 10)) . '/' . Session::get('year_reference') !!}</h3>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-bordered" id="account-statements-table">
                    <tr>
                        <td colspan="6">{{ 'Previous Balance: R$ '. number_format($previous_balance, 2, ',', '.')  }}</td>
                    </tr>
                    <tr>
                        <th>Emission</th>
                        <th>Expiration</th>
                        <th>Operation</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($movements as $movement)
                        <tr>
                            <td>{{ date_format($movement->emission_date,"d-m-Y") }}</td>
                            <td>{{ date_format($movement->maturity_date,"d-m-Y") }}</td>
                            <td>{{ $movement->operation }}</td>
                            <td>{{ $movement->status }}</td>
                            <td>{{ 'R$ '. number_format($movement->value, 2, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" style="width:130px;">
                                    {!! Form::open(['action' => ['MovementController@destroy', $movement->id], 'method' => 'delete', 'onsubmit' => 'return confirmDelete()']) !!}
                                        {{ link_to_route('movements.show', 'View', $movement->id, ['class' => 'btn btn-default   btn-xs']) }}

                                        {{ link_to_route('movements.edit', 'Edit', $movement->id, ['class' => 'btn btn-default btn-xs']) }}

                                        {!! Form::submit('Delete',['class'=>'btn btn-danger btn-xs']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6">{{ 'Credits: R$ '. number_format($credits, 2, ',', '.') . ' ' . 'Debits: R$ ' . number_format($debits, 2, ',', '.') . ' ' . 'Balance: R$ ' . number_format(($previous_balance + $credits - $debits), 2, ',', '.')  }}</td>
                    </tr>
                </table>
                
                <br>

                <section class="content-footer">
                    <div class="dt-buttons btn-group" style="margin-left:0px; margin-bottom:10px">
                        {{ link_to_route('bankAccounts.previous_month', '', $bank_account->id, ['class' => 'btn btn-default fa fa-chevron-left']) }}
                        <div class="btn btn-default">
                            {{ Session::get('month_reference') . ' / ' . Session::get('year_reference') }}
                        </div>
                        {{ link_to_route('bankAccounts.next_month', '', $bank_account->id, ['class' => 'btn btn-default fa fa-chevron-right']) }}
                        <a class="btn btn-default" href="{!! route('bankAccounts.index') !!}">Voltar</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
@endsection
