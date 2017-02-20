@push('scripts')
    <script>
        $('#description').keyup(function(){
		    this.value = this.value.toUpperCase();
		});
    </script>
@endpush
<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('generate_invoice', 'Generate Invoice?') !!}
    {!! Form::select('generate_invoice', array( true => 'YES', 0 => 'NO'), 0,['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('paymentForms.index') !!}" class="btn btn-default">Cancel</a>
</div>
