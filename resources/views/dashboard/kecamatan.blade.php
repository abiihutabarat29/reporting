@extends('layouts.app')
@section('content')
    @if ($banner->banner)
        <img class='img-rounded mb-5' src="{{ asset('storage/foto-banner/' . $banner->banner) }}" alt="Banner" width="100%">
    @else
        <img class='img-rounded mb-5' src="assets/img/images.png" alt="Banner" width="100%" height="350px">
        <div class="profile-container mb-5">
            <div class="card bg-white rounded-lg" style="display: flex; justify-content: center; align-items: center;">
                <div class="block">
                    <h4 class="text-center text-danger font-weight-bold"><i class="fa fa-info-circle"></i> Silahkan update
                        banner pada profil anda (foto seluruh anggota struktural PKK di instansi) dengan <b>Ukuran 1920 x
                            720 pixel</b>.
                        </h6>
                </div>
            </div>
        </div>
        <div class="m-5" style="display: flex; justify-content: center; align-items: center;">
            <a href="{{ route('profil') }}" class="btn btn-warning btn-sm">Update sekarang</a>
        </div>
    @endif
    <ol class="breadcrumb pull-right">
        <li class="active">Daftar Kegiatan Saya</li>
    </ol>
    <h1 class="page-header">Daftar Kegiatan Saya</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="result-container">
                <ul class="result-list">
                    @if ($data->isEmpty())
                        <div class="col-lg-12 col-md-6">
                            <div class="card bg-white p-6 m-5 rounded-lg"
                                style="display: flex; justify-content: center; align-items: center;">
                                <div class="block">
                                    <h3 class="text-judul text-center">Kegiatan Saya belum ada.</h3>
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
