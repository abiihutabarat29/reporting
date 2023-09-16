@extends('layouts.app')
@section('content')
    <ol class="breadcrumb pull-right">
        <li class="active">Dashboard</li>
    </ol>
    <h1 class="page-header">Dashboard {{ Auth::user()->name }}</h1>
@endsection
