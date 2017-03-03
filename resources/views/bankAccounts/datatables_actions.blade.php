{!! Form::open(['route' => ['bankAccounts.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('bankAccounts.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route('bankAccounts.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!}

    <a href="{{ route('bankAccounts.account_statement', [$id, Session::get('month_reference')]) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-list-alt"></i>
    </a>
</div>
{!! Form::close() !!}
