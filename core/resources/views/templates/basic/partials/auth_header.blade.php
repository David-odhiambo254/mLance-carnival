<header class="header-wrapper">
    <div class="bottom-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <div class="collapse navbar-collapse d-none d-lg-block" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.home') }}" href="{{ route('user.home') }}"> <i
                                    class="las la-tachometer-alt"></i>
                                @lang('Dashboard')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.gig.*') }}" href="{{ route('user.gig.list') }}">
                                <i class="las la-list"></i>
                                @lang('Gigs')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.project*') }}"
                                href="{{ route('user.project.pending') }}"> <i class="las la-list-ol"></i>
                                @lang('Projects')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.deposit*') }}"
                                href="{{ route('user.deposit.index') }}"> <i class="las la-wallet"></i>
                                @lang('Deposit')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.withdraw*') }}" href="{{ route('user.withdraw') }}">
                                <i class="las la-credit-card"></i>
                                @lang('Withdraw')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.transactions') }}"
                                href="{{ route('user.transactions') }}"> <i class="las la-exchange-alt"></i>
                                @lang('Transactions')</a>
                        </li>

                    </ul>
                    <div class="gig-btn-wrapper">
                        <a class="btn btn--base" href="{{ route('user.gig.overview') }}"> <i class="fas fa-plus"></i>
                            @lang('Create Gig')</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="sidebar-menu">
    <div class="sidebar-menu-wrapper">
        <div class="cros-btn sidebar-menu__close text-end">
            <button>
                <i class="las la-times"></i>
            </button>
        </div>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ menuActive('user.home') }}" href="{{ route('user.home') }}"> <i
                        class="las la-tachometer-alt"></i>
                    @lang('Dashboard')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ menuActive('user.gig.list') }}" href="{{ route('user.gig.list') }}">
                    <i class="las la-list"></i>
                    @lang('Gigs')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ menuActive('user.project.*') }}" href="{{ route('user.project.pending') }}"> <i
                        class="las la-list-ol"></i>
                    @lang('Projects')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ menuActive('user.deposit*') }}" href="{{ route('user.deposit.index') }}"> <i
                        class="las la-wallet"></i>
                    @lang('Deposit')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ menuActive('user.withdraw*') }}" href="{{ route('user.withdraw') }}">
                    <i class="las la-credit-card"></i>
                    @lang('Withdraw')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ menuActive('user.transactions') }}" href="{{ route('user.transactions') }}"> <i
                        class="las la-exchange-alt"></i>
                    @lang('Transactions')</a>
            </li>
        </ul>
        <div class="gig-btn-wrapper mt-4">
            <a class="btn btn--base w-100" href="{{ route('user.gig.overview') }}"> <i class="fas fa-plus"></i>
                @lang('Create Gig')</a>
        </div>
    </div>
</div>
