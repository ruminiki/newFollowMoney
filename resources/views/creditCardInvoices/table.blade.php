<table class="table table-responsive" id="creditCardInvoices-table">
    <thead>
        <th>Credit Card Id</th>
        <th>User Id</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($creditCardInvoices as $creditCardInvoice)
        <tr>
            <td>{!! $creditCardInvoice->credit_card_id !!}</td>
            <td>{!! $creditCardInvoice->user_id !!}</td>
            <td>
                {!! Form::open(['route' => ['creditCardInvoices.destroy', $creditCardInvoice->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('creditCardInvoices.show', [$creditCardInvoice->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('creditCardInvoices.edit', [$creditCardInvoice->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>