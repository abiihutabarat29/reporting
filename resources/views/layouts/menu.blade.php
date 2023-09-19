<div id="header" class="header navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="{{ url('/dashboard') }}" class="navbar-brand"> E-REPORTING</a>
            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="assets/img/blank.jpg" alt="" />
                    <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <li><a href="#">Profile</a></li>
                    <li>
                        <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"
                            onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div id="sidebar" class="sidebar">
    <div data-scrollbar="true" data-height="100%">
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <a href="javascript:;"><img src="assets/img/blank.jpg" alt="" /></a>
                </div>
                <div class="info">
                    {{ Auth::user()->name }}
                    <small>{{ Auth::user()->email }}</small>
                </div>
            </li>
        </ul>
        <ul class="nav">
            <li class="nav-header">Menu</li>
            <x-menu link="{{ route('dashboard') }}" icon="fa fa-laptop" label="Dashboard" active="dashboard"></x-menu>
            @if (Auth::user()->role == 1)
                <x-menuDropdown icon="fa fa-file" label="Master Data"
                    active="{{ request()->segment(1) == 'kecamatan' ||
                    request()->segment(1) == 'desa' ||
                    request()->segment(1) == 'user-kecamatan' ||
                    request()->segment(1) == 'bidang' ||
                    request()->segment(1) == 'program-kerja' ||
                    request()->segment(1) == 'kegiatan' ||
                    request()->segment(1) == 'user-desa'
                        ? 'active'
                        : '' }}">
                    <x-linkDropdown link="{{ route('kecamatan.index') }}" label="Kecamatan" active="kecamatan">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('desa.index') }}" label="Desa/Kelurahan" active="desa">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('user-kecamatan.index') }}" label="User Kecamatan"
                        active="user-kecamatan">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('user-desa.index') }}" label="User Desa/Kelurahan"
                        active="user-desa">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('bidang.index') }}" label="Data Bidang" active="bidang">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('program-kerja.index') }}" label="Data Program Kerja"
                        active="program-kerja">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('kegiatan.index') }}" label="Data Kegiatan" active="kegiatan">
                    </x-linkDropdown>
                </x-menuDropdown>
            @else
                <x-menuDropdown icon="fa fa-file" label="Data Kegiatan"
                    active="{{ request()->segment(1) == 'kegiatan-saya' ||
                    request()->segment(1) == 'kegiatan-kecamatan' ||
                    request()->segment(1) == 'kegiatan-desa-kelurahan'
                        ? 'active'
                        : '' }}">
                    <x-linkDropdown link="#" label="Kegiatan Saya" active="kegiatan-saya">
                    </x-linkDropdown>
                    <x-linkDropdown link="#" label="Kegiatan Kecamatan" active="kegiatan-kecamatan">
                    </x-linkDropdown>
                    <x-linkDropdown link="#" label="Kegiatan Desa/Kelurahan" active="kegiatan-desa-kelurahan">
                    </x-linkDropdown>
                </x-menuDropdown>
            @endif
            <x-menuDropdown icon="fa fa-book" label="Rekap Laporan"
                active="{{ request()->segment(1) == 'daftar-laporan' ? 'active' : '' }}">
                <x-linkDropdown link="#" label="Daftar Laporan" active="daftar-laporan">
                </x-linkDropdown>
            </x-menuDropdown>
            <x-menuDropdown icon="fa fa-bar-chart-o" label="Ranking"
                active="{{ request()->segment(1) == 'ranking-proker' || request()->segment(1) == 'ranking-laporan' ? 'active' : '' }}">
                <x-linkDropdown link="#" label="Rangking Proker" active="ranking-proker">
                </x-linkDropdown>
                <x-linkDropdown link="#" label="Ranking Laporan Kegiatan" active="rangking-laporan-kegiatan">
                </x-linkDropdown>
            </x-menuDropdown>
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i
                        class="fa fa-angle-double-left"></i></a></li>
        </ul>
    </div>
</div>
<div class="sidebar-bg"></div>
