<!-- Emission Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('emission_date', 'Emission Date:') !!}
    {!! Form::text('emission_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Maturity Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('maturity_date', 'Maturity Date:') !!}
    {!! Form::text('maturity_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('value', 'Value:') !!}
    {!! Form::text('value', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Paid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount_paid', 'Amount Paid:') !!}
    {!! Form::text('amount_paid', null, ['class' => 'form-control']) !!}
</div>

<!-- Reference Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reference_month', 'Reference Month:') !!}
    {!! Form::text('reference_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Reference Year Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reference_year', 'Reference Year:') !!}
    {!! Form::text('reference_year', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Credit Card Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('credit_card_id', 'Credit Card Id:') !!}
    {!! Form::text('credit_card_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('creditCardInvoices.index') !!}" class="btn btn-default">Cancel</a>
</div>
