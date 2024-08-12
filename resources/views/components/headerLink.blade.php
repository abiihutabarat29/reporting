@props(['link', 'menu'])
<ol class="breadcrumb pull-right">
    <li><a href="{{ $link }}">Dashboard</a></li>
    <li class="active">{{ $menu }}</li>
</ol>
<h1 class="page-header">{{ $menu }}</h1>
