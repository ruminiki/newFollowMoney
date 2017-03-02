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

        $("#bank_account_id").change(function(){
            var id = $("#bank_account_id").val();
            var month_reference = $("#month_reference").val();
            request(id, month);
        });

        $('[id^="month_"]').click(function(){
            $('.active').attr('class','btn btn-default pull-left btn-sm');
            $('#'+this.id).attr('class','btn btn-default pull-left btn-sm active');

            var id = $("#bank_account_id").val();
            var month_reference = $('#'+this.id).attr('month');
            request(id, month);

        });

        function request(id, month){
            if ( id > 0 ){
                var url = "/bankAccounts/".concat(id ,"/account_statement/", month);
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: { id: id, year: year },
                    enctype: 'multipart/form-data',
                    success: function(data){
                        $('#container').replaceWith(data.html);
                    },
                    error: function(){
                        alert('Erro ao carregar extrato !');
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
                {!! Form::label('bank_account_id', 'Bank Account:') !!}
                {!! Form::select('bank_account_id', 
                     $bank_accounts->all('description'), 
                     $bank_account->id,
                     ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
            </div>
            
            <div class="row">
                {!! Form::label('bank_account_id', 'Month Reference:') !!}
                <div id="buttons_container" class="col-sm-6">
                    <div class="btn btn-default pull-left btn-sm active">{{ Session::get('year_reference') }}</div>
                    @foreach (range(1, 12) as $month)

                        {{ Form::button(date('M', mktime(0, 0, 0, $month, 10)),
                            ['class' => $month == Session::get('month_reference') ? 'btn btn-default pull-left btn-sm active' : 'btn btn-default pull-left btn-sm', 'width'=> '80px',  'id'=>'month_'.$month, 'name'=>'month_'.$month, 'month'=>$month] ) }}
                        
                    @endforeach
                </div>
            </div> 
        </div>
    </section>

    @include('bankAccounts.account_statement_content')

@endsection
