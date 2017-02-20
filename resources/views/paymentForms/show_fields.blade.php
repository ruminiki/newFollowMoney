<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $paymentForm->id !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $paymentForm->description !!}</p>
</div>

<div class="form-group">
    {!! Form::label('generate_invoice', 'Generate Invoice:') !!}
    <p>{!! $paymentForm->generate_invoice !!}</p>
</div>