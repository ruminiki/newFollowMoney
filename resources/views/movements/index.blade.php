@extends('layouts.app')

@push('scripts')
    <script type="text/javascript">

        /*$('[id^="month_"]').click(function(){
            $('#buttons_month_container').children().attr('class','btn btn-default pull-right btn-sm');
            $('#'+this.id).attr('class','btn btn-default pull-right btn-sm active');
            var month = $('#'+this.id).attr('month');
            var url = "/movements/month/" + month;

            $.ajax({
                type: 'GET',
                url: url,
                data: { month: month },
                enctype: 'multipart/form-data',
                success: function(data){
                    //$('#container').replaceWith(data.html);
                    //alert(data.html);
                },
                error: function(){
                    alert('Erro ao carregar os lançamentos!');
                }
            },"html");  

        });*/

    </script>
@endpush

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
                        <td colspan="3">
                            {{ 'Previous Balance: R$ '. number_format($previous_balance, 2, ',', '.') . '   ' .
                               'Month Balance: R$ ' . number_format($month_balance, 2, ',', '.') . '   ' .
                               'Estimated Balance: R$ ' . number_format($previous_balance + $month_balance, 2, ',', '.') }}
                        </td>
                        <td colspan="3">
                            <div class="row">
                                <div id="buttons_month_container" class="col-sm-12">
                                    
                                    @foreach (range(12, 1) as $month)

                                        {{ link_to_route('movements.month', date('M', mktime(0, 0, 0, $month, 10)), $month, ['class' => $month == Session::get('month_reference') ? 'btn btn-default pull-right btn-sm active' : 'btn btn-default pull-right btn-sm', 'month'=>$month]) }}

                                    @endforeach
                                    {{ link_to_route('movements.next_month', '', 0, ['class' => 'btn btn-default fa fa-chevron-right pull-right btn-sm', 'style'=>'margin-left:0px']) }}
                                    <div class="btn btn-default pull-right btn-sm active">{{ Session::get('year_reference') }}</div>
                                    {{ link_to_route('movements.previous_month', '', 0, ['class' => 'btn btn-default fa fa-chevron-left pull-right btn-sm']) }}
                                </div>
                            </div> 
                        </td>
                    </tr>
                </table>
                <br>

                @include('movements.table')

            </div>
            
        </div>
    </div>
@endsection

