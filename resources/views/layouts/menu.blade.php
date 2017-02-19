<li class="{{ Request::is('categories*') ? 'active' : '' }}">
    <a href="{!! route('categories.index') !!}"><i class="fa fa-edit"></i><span>Categories</span></a>
</li>

<li class="{{ Request::is('bankAccounts*') ? 'active' : '' }}">
    <a href="{!! route('bankAccounts.index') !!}"><i class="fa fa-edit"></i><span>Bank Accounts</span></a>
</li>

<li class="{{ Request::is('creditCards*') ? 'active' : '' }}">
    <a href="{!! route('creditCards.index') !!}"><i class="fa fa-edit"></i><span>Credit Cards</span></a>
</li>

<li class="{{ Request::is('creditCardInvoices*') ? 'active' : '' }}">
    <a href="{!! route('creditCardInvoices.index') !!}"><i class="fa fa-edit"></i><span>Credit Card Invoices</span></a>
</li>

<li class="{{ Request::is('paymentForms*') ? 'active' : '' }}">
    <a href="{!! route('paymentForms.index') !!}"><i class="fa fa-edit"></i><span>Payment Forms</span></a>
</li>

