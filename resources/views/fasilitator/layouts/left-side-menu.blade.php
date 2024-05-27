<ul class="metismenu" id="menu">
    @if (request()->routeIs('fasilitator.dashboard.index'))
        <li class="mm-active">
    @else
        <li>
    @endif
        <a href="{{ route('fasilitator.dashboard.index') }}">
            <div class="parent-icon"><i class='bx bx-home-alt'></i>
            </div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>
    @if (request()->routeIs('fasilitator.bkk.index'))
        <li class="mm-active">
    @else
        <li>
    @endif
        <a href="{{ route('fasilitator.bkk.index') }}">
            <div class="parent-icon"><i class='bx bx-data'></i>
            </div>
            <div class="menu-title">BKK</div>
        </a>
    </li>
</ul>
