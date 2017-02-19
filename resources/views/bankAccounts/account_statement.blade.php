@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! $bankAccount->description . ' - ' . $bankAccount->number !!}</h1>
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
                            <th>Date</th>
                            <th>Movement</th>
                            <th>Type</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    @foreach($account_statements as $key => $array)
                        <tr>
                        @foreach($array as $movement => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
