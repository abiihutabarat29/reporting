@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1">
        <div class="panel-heading">
            <h4 class="panel-title">Filter Berdasarkan Tanggal</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5">
                        <x-inputDate type="date" name="date_start" label="Dari"></x-inputDate>
                    </div>
                    <div class="col-md-5">
                        <x-inputDate type="date" name="date_end" label="Sampai"></x-inputDate>
                    </div>
                    <div class="col-md-2">
                        <button id="resetFilter" class="btn btn-white btn-sm">Batal</button>
                        <button id="applyFilter" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-datatableRanking submenu="{{ $submenu }}">
        <th style="width:5%">No</th>
        <th>Kecamatan</th>
        <th style="width: 40%">PKK</th>
        <th class="text-center" style="width: 12%">Jumlah Kegiatan</th>
        <th class="text-center" style="width: 10%">Peringkat</th>
        <th class="text-center" style="width: 8%">Action</th>
    </x-datatableRanking>
@endsection
@section('modal')
    <x-detail>
        <x-datatableDetail>
            <th style="width:5%">No</th>
            <th>Judul</th>
            <th style="width:15%">Bidang</th>
            <th style="width:17%">Program Kerja</th>
            <th style="width:15%">Kegiatan</th>
            <th style="width:12%">Tanggal</th>
            <th class="text-center" style="width:8%">Action</th>
        </x-datatableDetail>
    </x-detail>
@endsection
@section('script')
    <script text="javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var myTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                pageLength: 20,
                lengthMenu: [10, 50, 100, 200, 500],
                lengthChange: true,
                autoWidth: true,
                scrollCollapse: true,
                paging: true,
                ajax: {
                    url: '{{ route('ranking-kecamatan') }}',
                    data: function(d) {
                        d.date_start = $('input[name="date_start"]').val();
                        d.date_end = $('input[name="date_end"]').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                    },
                    {
                        data: "kecamatan",
                        name: "kecamatan",
                    },
                    {
                        data: "pkk",
                        name: "pkk",
                    },
                    {
                        data: "jumlah",
                        name: "jumlah",
                    },
                    {
                        data: "peringkat",
                        name: "peringkat",
                    },
                    {
                        data: "detail",
                        name: "detail",
                    },
                ],
            });

            $('#applyFilter').on('click', function() {
                myTable.ajax.reload();
            });
            $('#resetFilter').on('click', function() {
                $('input[name="date_start"]').val('');
                $('input[name="date_end"]').val('');
                myTable.ajax.reload();
            });

            var kecamatanTable = null;
            $("body").on("click", ".detail", function() {
                kecamatan_id = $(this).data("id");
                var pkkName = $(this).data("name");
                $("#detailModal").modal("show");
                $("#modelHeading").html('Detail Kegiatan' + ' ' + pkkName);
                if (kecamatanTable) {
                    kecamatanTable.destroy();
                }
                kecamatanTable = $('#datatableDetail').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    pageLength: 5,
                    lengthMenu: [10, 50, 100, 200, 500],
                    lengthChange: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('laporan-kecamatan-detail') }}",
                        data: function(d) {
                            d.kecamatan_id = kecamatan_id;
                        }
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex",
                        },
                        {
                            data: "name",
                            name: "name",
                        },
                        {
                            data: "bidang",
                            name: "bidang",
                        },
                        {
                            data: "program",
                            name: "program",
                        },
                        {
                            data: "kegiatan",
                            name: "kegiatan",
                        },
                        {
                            data: "date",
                            name: "date",
                        },
                        {
                            data: "foto",
                            name: "foto",
                        },
                    ],
                });
            });
            $(document).ready(function() {
                $('.popup-link').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });
            });
        });
    </script>
@endsection
