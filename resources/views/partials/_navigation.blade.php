<div class="collapse navbar-collapse" id="app-navbar-collapse">
    <!-- Left Side Of Navbar -->
    <ul class="nav navbar-nav">
        @unless ($userIsGuest)
            @if ($loggedInUserIsAdmin)
                <li>
                    <a href="{{ url('/users') }}"><i class="fa fa-btn fa-user"></i>Users</a>
                </li>
            @endif
            <li>
                <a href="{{ url('/users/' . $loggedInUser->id . '/timesheets') }}">
                    <i class="fa fa-btn fa-calendar"></i>My Timesheets
                </a>
            </li>
            <li>
                <a href="{{ url('/preferences') }}" @click.prevent="showUserPreferencesModal()">
                    <i class="fa fa-btn fa-cog"></i>Preferences
                </a>
            </li>
        @endunless
    </ul>

    <!-- Right Side Of Navbar -->
    <ul class="nav navbar-nav navbar-right">
        <!-- Authentication Links -->
        @if ($userIsGuest)
            <li><a href="{{ url('/login') }}">Login</a></li>
            <li><a href="{{ url('/register') }}">Register</a></li>
        @else
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    {{ $loggedInUser->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                </ul>
            </li>
        @endif
    </ul>
</div>

<modal-user-preferences title="Preferences"></modal-user-preferences>