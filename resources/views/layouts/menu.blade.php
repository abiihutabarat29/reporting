<div id="header" class="header navbar navbar-default navbar-fixed-top bg-primary">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="{{ url('/dashboard') }}" class="navbar-brand"><span><img src="assets/img/LOGO_PKK_PNG.png"
                        alt="Logo PKK" width="30px"></span>
                E-REPORTING</a>
            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    @if (Auth::user()->foto == null)
                        <img src="{{ url('assets/img/blank.jpg') }}">
                    @else
                        <img src="{{ url('storage/foto-users/' . Auth::user()->foto) }}">
                    @endif
                    <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <li><a href="{{ route('profil') }}">Profile</a></li>
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
                    <a href="{{ route('profil') }}">
                        @if (Auth::user()->foto == null)
                            <img src="{{ url('assets/img/blank.jpg') }}">
                        @else
                            <img src="{{ url('storage/foto-users/' . Auth::user()->foto) }}">
                        @endif
                    </a>
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
                    request()->segment(1) == 'bidang' ||
                    request()->segment(1) == 'program-kerja' ||
                    request()->segment(1) == 'kegiatan' ||
                    request()->segment(1) == 'user'
                        ? 'active'
                        : '' }}">
                    <x-linkDropdown link="{{ route('kecamatan.index') }}" label="Kecamatan" active="kecamatan">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('desa.index') }}" label="Desa/Kelurahan" active="desa">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('user.index') }}" label="User" active="user">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('bidang.index') }}" label="Data Bidang" active="bidang">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('program-kerja.index') }}" label="Data Program Kerja"
                        active="program-kerja">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('kegiatan.index') }}" label="Data Kegiatan" active="kegiatan">
                    </x-linkDropdown>
                </x-menuDropdown>
            @endif
            @if (Auth::user()->role == 2 || Auth::user()->role == 3)
                <x-menuDropdown icon="fa fa-file" label="Data Anggota"
                    active="{{ request()->segment(1) == 'anggota' ? 'active' : '' }}">
                    <x-linkDropdown link="{{ route('anggota.index') }}" label="Anggota" active="anggota">
                    </x-linkDropdown>
                </x-menuDropdown>
            @endif
            <x-menuDropdown icon="fa fa-file" label="Data Kegiatan"
                active="{{ request()->segment(1) == 'laporan' ||
                request()->segment(1) == 'kegiatan-kabupaten' ||
                request()->segment(1) == 'kegiatan-kecamatan' ||
                request()->segment(1) == 'kegiatan-desa-kelurahan'
                    ? 'active'
                    : '' }}">
                <x-linkDropdown link="{{ route('laporan.index') }}" label="Kegiatan Saya" active="laporan">
                </x-linkDropdown>
                @if (Auth::user()->role != 1)
                    <x-linkDropdown link="{{ route('laporan.kabupaten') }}" label="Kegiatan Kabupaten"
                        active="kegiatan-kabupaten">
                @endif
                </x-linkDropdown>
                <x-linkDropdown link="{{ route('laporan.kecamatan') }}" label="Kegiatan Kecamatan"
                    active="kegiatan-kecamatan">
                </x-linkDropdown>
                <x-linkDropdown link="{{ route('laporan.desa') }}" label="Kegiatan Desa/Kelurahan"
                    active="kegiatan-desa-kelurahan">
                </x-linkDropdown>
            </x-menuDropdown>
            @if (Auth::user()->role == 1)
                <x-menuDropdown icon="fa fa-users" label="Keanggotaan PKK"
                    active="{{ request()->segment(1) == 'anggota' || request()->segment(1) == 'keanggotaan-kecamatan' || request()->segment(1) == 'keanggotaan-desa-kelurahan' ? 'active' : '' }}">
                    <x-linkDropdown link="{{ route('anggota.index') }}" label="Kabupaten" active="anggota">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('keanggotaan-kecamatan') }}" label="Kecamatan"
                        active="keanggotaan-kecamatan">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('keanggotaan-desa-kelurahan') }}" label="Desa/Kelurahan"
                        active="keanggotaan-desa-kelurahan">
                    </x-linkDropdown>
                </x-menuDropdown>
                <x-menuDropdown icon="fa fa-bar-chart-o" label="Ranking"
                    active="{{ request()->segment(1) == 'ranking-laporan' ? 'active' : '' }}">
                    <x-linkDropdown link="{{ route('ranking-kecamatan') }}" label="Ranking Kecamatan"
                        active="ranking-kecamatan">
                    </x-linkDropdown>
                    <x-linkDropdown link="{{ route('ranking-desa-kelurahan') }}" label="Ranking Desa/Kelurahan"
                        active="ranking-desa-kelurahan">
                    </x-linkDropdown>
                </x-menuDropdown>
            @endif
            <hr>
            <x-menu link="{{ route('download-buku-panduan') }}" icon="fa fa-book" label="Buku Panduan"
                active="buku-panduan"></x-menu>
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i
                        class="fa fa-angle-double-left"></i></a></li>
        </ul>
    </div>
</div>
<div class="sidebar-bg"></div>
