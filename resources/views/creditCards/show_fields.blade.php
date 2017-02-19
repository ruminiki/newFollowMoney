<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $creditCard->id !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $creditCard->description !!}</p>
</div>

<!-- Limit Field -->
<div class="form-group">
    {!! Form::label('limit', 'Limit:') !!}
    <p>{!! $creditCard->limit !!}</p>
</div>

<!-- Invoice Date Field -->
<div class="form-group">
    {!! Form::label('invoice_day', 'Invoice Date:') !!}
    <p>{!! $creditCard->invoice_day !!}</p>
</div>

<!-- Closing Date Field -->
<div class="form-group">
    {!! Form::label('closing_day', 'Closing Date:') !!}
    <p>{!! $creditCard->closing_day !!}</p>
</div>


