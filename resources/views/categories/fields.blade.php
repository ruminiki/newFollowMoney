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

<!-- Category Superior Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category_superior_id', 'Superior Category:') !!}
	{!! Form::select('category_superior_id', 
					 $categories->all('description'), 
					 empty($category->categorySuperior) ? -1 : $category->categorySuperior->id, 
					 ['placeholder' => 'SELECT...', 'class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('categories.index') !!}" class="btn btn-default">Cancel</a>
</div>
