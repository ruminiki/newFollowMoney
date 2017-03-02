<li class="{{ Request::is('categories*') ? 'active' : '' }}">
    <a href="{!! route('categories.index') !!}"><i class="fa fa-edit"></i><span>Categories</span></a>
</li>

<li class="{{ Request::is('bankAccounts*') ? 'active' : '' }}">
    <a href="{!! route('bankAccounts.index') !!}"><i class="fa fa-address-card"></i><span>Bank Accounts</span></a>
</li>

<li class="{{ Request::is('bankAccounts*') ? 'active' : '' }}">
    <a href="{!! route('bankAccounts.account_statement', ['bank_account_id'=>1, 'month'=>Session::get('month_reference')]) !!}"><i class="fa fa-address-card"></i><span>Bank Account Statement</span></a>
</li>

<li class="{{ Request::is('creditCards*') ? 'active' : '' }}">
    <a href="{!! route('creditCards.index') !!}"><i class="fa fa-credit-card"></i><span>Credit Cards</span></a>
</li>

<li class="{{ Request::is('creditCardInvoices*') ? 'active' : '' }}">
    <a href="{!! route('creditCardInvoices.index') !!}"><i class="fa fa-bars"></i><span>Credit Card Invoices</span></a>
</li>

<li class="{{ Request::is('paymentForms*') ? 'active' : '' }}">
    <a href="{!! route('paymentForms.index') !!}"><i class="fa fa-eur"></i><span>Payment Forms</span></a>
</li>

<li class="{{ Request::is('movements*') ? 'active' : '' }}">
    <a href="{!! route('movements.index') !!}"><i class="fa fa-list"></i><span>Movements</span></a>
</li>

