@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th style="width:10%">Kode Wilayah</th>
        <th style="width:50%">Kecamatan</th>
        <th class="text-center" style="width: 10%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <x-input type="number" name="kode_wilayah" label="Kode Wilayah"></x-input>
        <x-input type="text" name="name" label="Nama Kecamatan"></x-input>
    </x-ajaxModel>
    <x-delete></x-delete>
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
                pageLength: 10,
                lengthMenu: [10, 50, 100, 200, 500],
                lengthChange: true,
                autoWidth: true,
                scrollCollapse: true,
                paging: true,
                ajax: {
                    url: '{{ route('kecamatan.index') }}'
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                    },
                    {
                        data: "kode_wilayah",
                        name: "kode_wilayah",
                    },
                    {
                        data: "kecamatan",
                        name: "kecamatan",
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });

            // Create
            var createHeading = "Tambah Kecamatan";
            createModel(createHeading)

            // Edit
            var editUrl = "{{ route('kecamatan.index') }}";
            var editHeading = "Edit Kecamatan";
            var field = ['kode_wilayah',
                'name'
            ];

            editModel(editUrl, editHeading, field)

            // Save
            saveBtn("{{ route('kecamatan.store') }}", myTable);

            // Delete
            var fitur = "Kecamatan";
            var editUrl = "{{ route('kecamatan.index') }}";
            var deleteUrl = "{{ route('kecamatan.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)
        });
    </script>
@endsection
