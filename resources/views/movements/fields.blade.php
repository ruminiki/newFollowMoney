@push('scripts')
    <script src="/js/jquery.maskMoney.js" type="text/javascript"></script>

    <script>

        $('#value').maskMoney({
            prefix:'R$ ', 
            allowNegative: false, 
            thousands:'.', 
            decimal:',', 
            affixesStay: true}
        );

        $('#description').keyup(function(){
            this.value = this.value.toUpperCase();
        });

        $('#emission_date').datepicker({
            changeMonth: true,
            changeYear: true,
            buttonText: "Choose",
            altFormat: "dd-mm-yy",
            dateFormat: "yy-mm-dd"
        });

        $('#maturity_date').datepicker({
            changeMonth: true,
            changeYear: true,
            buttonText: "Choose",
            altFormat: "dd-mm-yy",
            dateFormat: "yy-mm-dd"
        });

    </script>
@endpush

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Emission Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('emission_date', 'Emission Date:') !!}
    {!! Form::date('emission_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Maturity Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('maturity_date', 'Maturity Date:') !!}
    {!! Form::date('maturity_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('value', 'Value:') !!}
    {!! Form::text('value', null, ['class' => 'form-control']) !!}
</div>

<!-- Operation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operation', 'Operation:') !!}
    {!! Form::select('operation', array('CREDIT' => 'CREDIT', 'DEBIT' => 'DEBIT'), 'DEBIT', ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', array('OPEN' => 'OPEN', 'PAID' => 'PAID'), 'OPEN', ['class' => 'form-control']) !!}
</div>

<!-- Payment Form Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_form_id', 'Payment Form:') !!}
    {!! Form::select('payment_form_id', 
                 $paymentForms->all('description'), 
                 empty($movement->paymentForm) ? -1 : $movement->paymentForm->id, 
                 ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
</div>

<!-- Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Category:') !!}
    {!! Form::select('category_id', 
                     $categories->all('description'), 
                     empty($movement->category) ? -1 : $movement->category->id, 
                     ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
</div>

<!-- Bank Account Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bank_account_id', 'Bank Account:') !!}
    {!! Form::select('bank_account_id', 
                 $bankAccounts->all('description'), 
                 empty($movement->bankAccount) ? -1 : $movement->bankAccount->id, 
                 ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
</div>

<!-- Credit Card Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('credit_card_id', 'Credit Card:') !!}
    {!! Form::select('credit_card_id', 
                 $creditCards->all('description'), 
                 empty($movement->creditCard) ? -1 : $movement->creditCard->id, 
                 ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('movements.index') !!}" class="btn btn-default">Cancel</a>
</div>
