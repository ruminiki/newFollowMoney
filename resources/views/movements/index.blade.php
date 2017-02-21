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
                    @include('movements.table')
            </div>

            <section class="content-footer">
                <div class="dt-buttons btn-group" style="margin-left:10px; margin-bottom:10px">
                    {{ link_to_route('movements.previous_month', '', 0, ['class' => 'btn btn-default fa fa-chevron-left']) }}
                    <div class="btn btn-default">
                        {{ Session::get('month_reference') . ' / ' . Session::get('year_reference') }}
                    </div>
                    {{ link_to_route('movements.next_month', '', 0, ['class' => 'btn btn-default fa fa-chevron-right']) }}
                </div>
            </section>
        </div>
    </div>

@endsection

