@extends('layouts.app')
@section('content')
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>
    <h1 class="page-header">Dashboard {{ Auth::user()->profil->nama_pkk }}</h1>
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="card-dashboard mb-5">
                <a href="{{ route('laporan.index') }}">
                    <div class="card-body">
                        <img src="{{ url('../assets/img/circle.svg') }}" class="card-img-absolute" alt="circle">
                        <div class="d-flex flex-start">
                            <div class="align-self-center">
                                <i class="fa fa-book fa-4x text-danger font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3>{{ $laporan }}</h3>
                                <span>TOTAL LAPORAN</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-dashboard mb-5">
                <a href="{{ route('user.index') }}">
                    <div class="card-body">
                        <div class="d-flex flex-start">
                            <div class="align-self-center">
                                <i class="fa fa-users fa-4x text-success font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3>{{ $userskec }}</h3>
                                <span>USER KECAMATAN</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-dashboard mb-5">
                <a href="{{ route('user.index') }}">
                    <div class="card-body">
                        <div class="d-flex flex-start">
                            <div class="align-self-center">
                                <i class="fa fa-users fa-4x text-warning font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3>{{ $usersdesa }}</h3>
                                <span>USER DESA/KELURAHAN</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-dashboard mb-5">
                <a href="{{ route('bidang.index') }}">
                    <div class="card-body">
                        <div class="d-flex flex-start">
                            <div class="align-self-center">
                                <i class="fa fa-th-large fa-4x text-primary font-large-2 float-left"></i>
                            </div>
                            <div class="media-body text-right">
                                <h3>{{ $bidang }}</h3>
                                <span>TOTAL BIDANG</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-green">
                <div class="stats-icon"><i class="fa fa-book"></i></div>
                <div class="stats-info">
                    <h4>TOTAL LAPORAN</h4>
                    <p>{{ $laporan }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ route('laporan.index') }}">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4>USER KECAMATAN</h4>
                    <p>{{ $userskec }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ route('user.index') }}">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-purple">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4>USER DESA/KELURAHAN</h4>
                    <p>{{ $usersdesa }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ route('user.index') }}">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-red">
                <div class="stats-icon"><i class="fa fa-th-large"></i></div>
                <div class="stats-info">
                    <h4>TOTAL BIDANG</h4>
                    <p>{{ $bidang }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ route('bidang.index') }}">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
    </div> --}}
    <h3>Daftar Kegiatan </h3>
    <div class="row">
        <div class="col-md-12">
            <div class="result-container">
                <ul class="result-list">
                    @if ($data->isEmpty())
                        <div class="col-lg-12 col-md-6">
                            <div class="card bg-white p-6 m-5 rounded-lg"
                                style="display: flex; justify-content: center; align-items: center;">
                                <div class="block">
                                    <h3 class="text-judul text-center">Kegiatan belum ada.</h3>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <a href="{{ route('laporan.index') }}" class="btn btn-primary btn-sm">Buat Laporan</a>
                            </div>
                        </div>
                    @else
                        @foreach ($data as $item)
                            <li>
                                <div class="result-image">
                                    <a href="{{ url('storage/foto-kegiatan/' . $item->foto) }}" class="popup-link"
                                        target="blank"><img src="{{ url('storage/foto-kegiatan/' . $item->foto) }}"
                                            alt="{{ $item->name }}">
                                    </a>
                                </div>
                                <div class="result-info">
                                    <h4 class="title">{{ Auth::user()->profil->nama_pkk }}</h4>
                                    <p class="location">{{ $item->bidang->name }},
                                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}</p>
                                    <h6 class="title">{{ $item->name }}</h6>
                                    <p class="desc">
                                        {{ $item->description }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {
                $('.popup-link').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: false
                    }
                });
            });
        </script>
    @endsection
