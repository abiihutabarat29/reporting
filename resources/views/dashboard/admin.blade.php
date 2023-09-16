@extends('layouts.app')
@section('content')
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>
    <h1 class="page-header">Dashboard {{ Auth::user()->name }}</h1>
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-green">
                <div class="stats-icon"><i class="fa fa-book"></i></div>
                <div class="stats-info">
                    <h4>TOTAL LAPORAN</h4>
                    <p>0</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4>USER KECAMATAN</h4>
                    <p>0</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-purple">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4>USER DESA/KELURAHAN</h4>
                    <p>0</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats bg-red">
                <div class="stats-icon"><i class="fa fa-th-large"></i></div>
                <div class="stats-info">
                    <h4>TOTAL BIDANG</h4>
                    <p>0</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Lihat Selengkapnya <i class="fa fa-arrow-circle-o-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection
