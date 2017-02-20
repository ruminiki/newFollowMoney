@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! $bank_account->description . ' - ' . $bank_account->number !!}</h1>
        <h1 class="pull-right">
           <a class="btn btn-default pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('bankAccounts.index') !!}">Voltar</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-bordered" id="account-statements-table">
                    <thead>
                        <tr>
                            <th>Emission</th>
                            <th>Expiration</th>
                            <th>Operation</th>
                            <th>Status</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    @foreach($movements as $movement)
                        <tr>
                            <td>{{ date_format($movement->emission_date,"d-m-Y") }}</td>
                            <td>{{ date_format($movement->maturity_date,"d-m-Y") }}</td>
                            <td>{{ $movement->operation }}</td>
                            <td>{{ $movement->status }}</td>
                            <td>{{ $movement->value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
