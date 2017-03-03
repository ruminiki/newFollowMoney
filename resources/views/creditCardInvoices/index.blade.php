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

        $("#credit_card_id").change(function(){
            var id = $("#credit_card_id").val();
            var year = $("#year_reference").val();
            request(id, year);
        });

        $('[id^="year_"]').click(function(){
            $('#buttons_year_container').children().attr('class','btn btn-default pull-left btn-sm');
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

        function pay(){
            var id = $("#credit_card_id").val();
            if ( id > 0 ){
                var url = "/creditCardInvoices/".concat(id ,"/pay/");
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: { id: id },
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

        function unpay(){
            var id = $("#credit_card_id").val();
            if ( id > 0 ){
                var url = "/creditCardInvoices/".concat(id ,"/unpay/");
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: { id: id },
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
        <div class="row">
            <div class="col-sm-6">
                {!! Form::label('credit_card_id', 'Credit Card:') !!}
                {!! Form::select('credit_card_id', 
                     $credit_cards->all('description'), 
                     !empty($credit_card) ? $credit_card->id : 0,
                     ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
            </div>
            
            <div class="row">
                {!! Form::label('credit_card_id', 'Year Reference:') !!}
                <div id="buttons_year_container" class="col-sm-6">
                    @foreach (range($min_year, $max_year) as $year)

                        {{ Form::button($year,
                            ['class' => $year == Session::get('year_reference') ? 'btn btn-default pull-left btn-sm active' : 'btn btn-default pull-left btn-sm', 'width'=> '80px',  'id'=>'year_'.$year, 'name'=>'year_'.$year, 'year'=>$year] ) }}
                        
                    @endforeach
                </div>
            </div> 
        </div>
    </section>

    @include('creditCardInvoices.table')

@endsection

