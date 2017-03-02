@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Movements</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('movements.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-bordered" id="account-statements-table">
                    <tr>
                        <td colspan="6">
                            {{ 'Previous Balance: R$ '. number_format($previous_balance, 2, ',', '.') . '   ' .
                               'Month Balance: R$ ' . number_format($month_balance, 2, ',', '.') . '   ' .
                               'Estimated Balance: R$ ' . number_format($previous_balance + $month_balance, 2, ',', '.') }}
                        </td>
                    </tr>
                </table>

                @include('movements.table')

                <section class="content-footer">
                    <div class="dt-buttons btn-group" style="margin-left:10px; margin-bottom:10px">
                        {{ link_to_route('movements.previous_month', '', 0, ['class' => 'btn btn-default fa fa-chevron-left']) }}
                        <div class="btn btn-default">
                            {{ Session::get('month_reference') . ' / ' . Session::get('year_reference') }}
                        </div>
                        {{ link_to_route('movements.next_month', '', 0, ['class' => 'btn btn-default fa fa-chevron-right']) }}
                    </div>
                </section>

                <!-- <section class="content-footer">
                    {{ Form::open(['url' => '/movements','id'=>'search']) }}
                    
                    <table class="table table-bordered" id="account-statements-table">
                        <tr>
                            <td colspan="6">
                                {{ Form::label('name', 'Search:') }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Categories <br>
                                {!! Form::select('category_id', 
                                     $categories->all('description'), 
                                     null, ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
                            <td>
                                Bank Accounts<br>
                                {!! Form::select('bank_account_id', 
                                     $bank_accounts->all('description'), 
                                     null, ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
                            </td>

                            <td>
                                Credit Cards<br>
                                {!! Form::select('credit_card_id', 
                                     $credit_cards->all('description'), 
                                     null, ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
                            </td>
                        </tr>
                    </table>
                    {{ Form::submit('Search', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </section> -->

            </div>
            
        </div>
    </div>
@endsection

