<li class="{{ Request::is('categories*') ? 'active' : '' }}">
    <a href="{!! route('categories.index') !!}"><i class="fa fa-edit"></i><span>Categories</span></a>
</li>

<li class="{{ Request::is('bankAccounts*') ? 'active' : '' }}">
    <a href="{!! route('bankAccounts.index') !!}"><i class="fa fa-edit"></i><span>Bank Accounts</span></a>
</li>

