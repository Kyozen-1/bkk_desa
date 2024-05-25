<ul class="metismenu" id="menu">
    @if (request()->routeIs('admin.dashboard.index'))
        <li class="mm-active">
    @else
        <li>
    @endif
        <a href="{{ route('admin.dashboard.index') }}">
            <div class="parent-icon"><i class='bx bx-cookie'></i>
            </div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>
</ul>
