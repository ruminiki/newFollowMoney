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
        <h1 class="pull-left">{!! $bank_account->description . ' - ' . $bank_account->number !!}</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-bordered" id="account-statements-table">
                    <tr>
                        <td colspan="6">{{ 'Balance: R$ '. number_format($credits, 2, ',', '.')  }}</td>
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
                            <td>{{ $movement->value }}</td>
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
                        <td colspan="6">{{ 'Credits: R$ '. number_format($credits, 2, ',', '.') . ' ' . 'Debits: R$ ' . number_format($debits, 2, ',', '.') . ' ' . 'Balance: R$ ' . number_format(($credits - $debits), 2, ',', '.')  }}</td>
                    </tr>
                </table>
                <section class="content-footer">
                    <h1 class="pull-left">
                       <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('bankAccounts.index') !!}">Voltar</a>
                    </h1>
                </section>
            </div>
        </div>
    </div>
    
@endsection
