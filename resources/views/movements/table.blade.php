@section('css')
    @include('layouts.datatables_css')
@endsection

{!! $dataTable->table(['width' => '100%']) !!}

@push('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endpush

{{ link_to_route('movements.show', 'Previous', 0, ['class' => 'btn btn-default   btn-xs']) }}
{{ Config::get('date_reference.month') . '/' . Config::get('date_reference.year') }}
{{ link_to_route('movements.edit', 'Next', 0, ['class' => 'btn btn-default btn-xs']) }}
