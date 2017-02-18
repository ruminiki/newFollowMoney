<table class="table table-responsive" id="bankAccounts-table">
    <thead>
        <th>Description</th>
        <th>Number</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($bankAccounts as $bankAccount)
        <tr>
            <td>{!! $bankAccount->description !!}</td>
            <td>{!! $bankAccount->number !!}</td>
            <td>
                {!! Form::open(['route' => ['bankAccounts.destroy', $bankAccount->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('bankAccounts.edit', [$bankAccount->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    {{ $bankAccounts->links() }}
    </tbody>
</table>