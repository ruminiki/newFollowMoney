@push('scripts')
    <script src="/js/jasny-bootstrap.min.js" type="text/javascript"></script>

    <script>
        $('#number').inputmask({
            mask: '999999-9'
        });

        $('#description').keyup(function(){
            this.value = this.value.toUpperCase();
        });

    </script>
@endpush
<!-- Description Field -->
<div class="form-group col-lg-5">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Number Field -->
<div class="form-group col-lg-3">
    {!! Form::label('number', 'Number:') !!}
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('bankAccounts.index') !!}" class="btn btn-default">Cancel</a>
</div>
