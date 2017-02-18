@extends('layouts.app')

@section('content')
    <table class="table table-bordered" id="categories-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Category Superior</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#categories-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: '{!! route('categories.search') !!}',
                "columnDefs": [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                columns: [
                    { data: 'id', name: 'id', searchable: false, orderable: true },
                    { data: 'description', name: 'description', searchable: true, orderable: true },
                    { data: 'category_superior', name: 'superior', searchable: false, orderable: false },
                    { data: 'created_at', name: 'created_at', searchable: false, orderable: false },
                    { data: 'updated_at', name: 'updated_at', searchable: false, orderable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>

@endpush

