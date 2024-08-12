@extends('layouts.app')
@section('content')
    <ol class="breadcrumb pull-right">
        <li class="active">{{ $menu }}</li>
    </ol>
    <h1 class="page-header">{{ $menu }}</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="result-container">
                <ul class="result-list">
                    @if ($data->isEmpty())
                        <div class="col-lg-12 col-md-6">
                            <div class="card bg-white p-6 mb-5 rounded-lg"
                                style="display: flex; justify-content: center; align-items: center;">
                                <div class="block">
                                    <h3 class="text-judul text-center">Kegiatan Kabupaten belum ada.</h3>
                                </div>
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
                                    <h4 class="title">{{ $item->nama_pkk }}</h4>
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
