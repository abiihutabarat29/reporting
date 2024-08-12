@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatableRanking submenu="{{ $submenu }}">
        <th style="width:5%">No</th>
        <th>Kecamatan</th>
        <th style="width: 40%">PKK</th>
        <th class="text-center" style="width: 10%">Jumlah</th>
        <th class="text-center" style="width: 8%">Action</th>
    </x-datatableRanking>
@endsection
@section('modal')
    <x-detail>
        <x-datatableDetail>
            <th style="width:5%">No</th>
            <th>Nama</th>
            <th style="width:20%">Email</th>
            <th style="width:15%">No. HP</th>
            <th class="text-center" style="width:10%">SK</th>
        </x-datatableDetail>
    </x-detail>
    <x-review></x-review>
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
                autoWidth: false,
                ajax: {
                    url: '{{ route('keanggotaan-kecamatan') }}'
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
                        data: "detail",
                        name: "detail",
                    },
                ],
            });
            var kecamatanTable = null;
            $("body").on("click", ".detail", function() {
                kecamatan_id = $(this).data("id");
                var pkkName = $(this).data("name");
                $("#detailModal").modal("show");
                $("#modelHeading").html('Detail Keanggotaan' + ' ' + pkkName);
                if (kecamatanTable) {
                    kecamatanTable.destroy();
                }
                kecamatanTable = $('#datatableDetail').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    pageLength: 10,
                    lengthMenu: [10, 50, 100, 200, 500],
                    lengthChange: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('keanggotaan-kecamatan-detail') }}",
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
                            data: "email",
                            name: "email",
                        },
                        {
                            data: "nohp",
                            name: "nohp",
                        },
                        {
                            data: "sk",
                            name: "sk",
                        },
                    ],
                });
            });
        });
        var dokumenUrl = "{{ url('get-sk') }}";
        var dokumenPath = "{{ asset('storage/sk') }}";
        Review(dokumenUrl, dokumenPath);
    </script>
@endsection
