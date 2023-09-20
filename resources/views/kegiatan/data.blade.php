@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th style="width:40%">Kegiatan</th>
        <th style="width:25%">Program Kerja</th>
        <th class="text-center" style="width: 10%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <x-dropdown name="program_id" label="Program Kerja">
            @foreach ($program as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
            @endforeach
        </x-dropdown>
        <x-input type="text" name="name" label="Nama Kegiatan"></x-input>
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
                    url: '{{ route('kegiatan.index') }}'
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
                        data: "program",
                        name: "program",
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
            var createHeading = "Tambah Kegiatan";
            createModel(createHeading)

            // Edit
            var editUrl = "{{ route('kegiatan.index') }}";
            var editHeading = "Edit Kegiatan";
            var field = ['program_id', 'name'];

            editModel(editUrl, editHeading, field)

            // Save
            saveBtn("{{ route('kegiatan.store') }}", myTable);

            // Delete
            var fitur = "Kegiatan";
            var editUrl = "{{ route('kegiatan.index') }}";
            var deleteUrl = "{{ route('kegiatan.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)
        });
    </script>
@endsection
