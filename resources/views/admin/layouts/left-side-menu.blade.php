<ul class="metismenu" id="menu">
    @if (request()->routeIs('admin.dashboard.index'))
        <li class="mm-active">
    @else
        <li>
    @endif
        <a href="{{ route('admin.dashboard.index') }}">
            <div class="parent-icon"><i class='bx bx-home-alt'></i>
            </div>
            <div class="menu-title">Dashboard</div>
        </a>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon">
                <i class="bx bx-category"></i>
            </div>
            <div class="menu-title">Peta</div>
        </a>
        <ul>
            @if (request()->routeIs('admin.peta-persebaran-bkk.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.peta-persebaran-bkk.index') }}"><i class='bx bx-radio-circle'></i>Persebaran BKK</a>
            </li>
            @if (request()->routeIs('admin.peta-per-kelurahan.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.peta-per-kelurahan.index') }}"><i class='bx bx-radio-circle'></i>Peta Per Kelurahan</a>
            </li>
        </ul>
    </li>
    @if (request()->routeIs('admin.bkk.index'))
        <li class="mm-active">
    @else
        <li>
    @endif
        <a href="{{ route('admin.bkk.index') }}">
            <div class="parent-icon"><i class='bx bx-data'></i>
            </div>
            <div class="menu-title">BKK</div>
        </a>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon">
                <i class="bx bx-category"></i>
            </div>
            <div class="menu-title">Master Data</div>
        </a>
        <ul>
            @if (request()->routeIs('admin.kecamatan.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.kecamatan.index') }}"><i class='bx bx-radio-circle'></i>Kecamatan</a>
            </li>
            @if (request()->routeIs('admin.kelurahan.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.kelurahan.index') }}"><i class='bx bx-radio-circle'></i>Kelurahan / Desa</a>
            </li>
            @if (request()->routeIs('admin.master-fraksi.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.master-fraksi.index') }}"><i class='bx bx-radio-circle'></i>Master Partai</a>
            </li>
            @if (request()->routeIs('admin.aspirator.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.aspirator.index') }}"><i class='bx bx-radio-circle'></i>Aspirator</a>
            </li>
            @if (request()->routeIs('admin.master-tipe-kegiatan.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.master-tipe-kegiatan.index') }}"><i class='bx bx-radio-circle'></i>Tipe Kegiatan</a>
            </li>
            @if (request()->routeIs('admin.master-jenis.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.master-jenis.index') }}"><i class='bx bx-radio-circle'></i>Jenis</a>
            </li>
            @if (request()->routeIs('admin.master-kategori-pembangunan.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.master-kategori-pembangunan.index') }}"><i class='bx bx-radio-circle'></i>Kategori Pembangunan</a>
            </li>
            @if (request()->routeIs('admin.tahun-periode.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.tahun-periode.index') }}"><i class='bx bx-radio-circle'></i>Tahun Periode</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon">
                <i class="bx bx-category"></i>
            </div>
            <div class="menu-title">Manajemen Akun</div>
        </a>
        <ul>
            @if (request()->routeIs('admin.manajemen-akun.fasilitator.index'))
                <li class="mm-active">
            @else
                <li>
            @endif
                <a href="{{ route('admin.manajemen-akun.fasilitator.index') }}"><i class='bx bx-radio-circle'></i>Fasilitator</a>
            </li>
        </ul>
    </li>
</ul>
