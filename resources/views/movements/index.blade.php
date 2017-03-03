@extends('layouts.app')

@push('scripts')
    <script type="text/javascript">
        $('[id^="month_"]').click(function(){
            $('#buttons_month_container').children().attr('class','btn btn-default pull-left btn-sm');
            $('#'+this.id).attr('class','btn btn-default pull-left btn-sm active');
            var id = $("#credit_card_id").val();
            var year = $('#'+this.id).attr('year');
            request(id, year);
        });

        function request(id, year){
            if ( id > 0 ){
                var url = "/creditCards/".concat(id ,"/invoices/", year);
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: { id: id, year: year },
                    enctype: 'multipart/form-data',
                    success: function(data){
                        $('#container').replaceWith(data.html);
                    },
                    error: function(){
                        alert('Erro ao carregar as faturas !');
                    }
                },"html");  
            }
        }
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

                                        {{ Form::button(date('M', mktime(0, 0, 0, $month, 10)),
                                            ['class' => $month == Session::get('month_reference') ? 'btn btn-default pull-right btn-sm active' : 'btn btn-default pull-right btn-sm', 'width'=> '80px',  'id'=>'month_'.$month, 'name'=>'month_'.$month, 'month'=>$month] ) }}
                                        
                                    @endforeach

                                    <div class="btn btn-default pull-right btn-sm active">{{ Session::get('year_reference') }}</div>
                                </div>
                            </div> 
                        </td>
                    </tr>
                </table>
                <br>
                
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

            </div>
            
        </div>
    </div>
@endsection

