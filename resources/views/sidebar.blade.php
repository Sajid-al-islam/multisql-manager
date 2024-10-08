<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Multi Query manager</div>
    </a>


    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->route()->getName() == 'query-convert' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('query-convert') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Convert Query</span></a>
    </li>

    <li class="nav-item {{ request()->route()->getName() == 'query-form' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('query-form') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Execute Query</span></a>
    </li>


    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
