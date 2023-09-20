@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th>Bidang</th>
        <th class="text-center" style="width: 15%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <x-input type="text" name="name" label="Nama Bidang"></x-input>
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
                    url: '{{ route('bidang.index') }}'
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
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });

            // Create
            var createHeading = "Tambah Bidang";
            createModel(createHeading)

            // Edit
            var editUrl = "{{ route('bidang.index') }}";
            var editHeading = "Edit Bidang";
            var field = ['name'];

            editModel(editUrl, editHeading, field)

            // Save
            saveBtn("{{ route('bidang.store') }}", myTable);

            // Delete
            var fitur = "Bidang";
            var editUrl = "{{ route('bidang.index') }}";
            var deleteUrl = "{{ route('bidang.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)
        });
    </script>
@endsection
