<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $category->id !!}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{!! $category->description !!}</p>
</div>

<!-- Category Superior Id Field -->
<div class="form-group">
    {!! Form::label('category_superior_id', 'Category Superior:') !!}
    <p>{!! empty($category->categorySuperior) ? '' : $category->categorySuperior->description !!}</p>
</div>

