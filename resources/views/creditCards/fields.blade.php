@push('scripts')
    <script src="/js/jasny-bootstrap.min.js" type="text/javascript"></script>
    <script src="/js/jquery.maskMoney.js" type="text/javascript"></script>

    <script>
        $('#invoice_day').inputmask({
            mask: '99'
        });

        $('#closing_day').inputmask({
            mask: '99'
        });

        $('#limit').maskMoney({
            prefix:'R$ ', 
            allowNegative: true, 
            thousands:'.', 
            decimal:',', 
            affixesStay: true}
        );


    </script>
@endpush

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Limit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('limit', 'Limit:') !!}
    {!! Form::text('limit', null, ['class' => 'form-control']) !!}
</div>

<!-- Invoice Date Field -->
<div class="form-group col-sm-1">
    {!! Form::label('invoice_day', 'Invoice Date:') !!}
    {!! Form::text('invoice_day', null, ['class' => 'form-control']) !!}
</div>

<!-- Closing Date Field -->
<div class="form-group col-sm-1">
    {!! Form::label('closing_day', 'Closing Date:') !!}
    {!! Form::text('closing_day', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('creditCards.index') !!}" class="btn btn-default">Cancel</a>
</div>
