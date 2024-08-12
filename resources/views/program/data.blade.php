@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th style="width:40%">Program Kerja</th>
        <th style="width:25%">Bidang</th>
        <th class="text-center" style="width: 10%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <x-dropdown name="bidang_id" label="Bidang">
            @foreach ($bidang as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
            @endforeach
        </x-dropdown>
        <x-input type="text" name="name" label="Nama Program"></x-input>
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
                    url: '{{ route('program-kerja.index') }}'
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
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });

            // Create
            var createHeading = "Tambah Program Kerja";
            createModel(createHeading)

            // Edit
            var editUrl = "{{ route('program-kerja.index') }}";
            var editHeading = "Edit Program Kerja";
            var field = ['bidang_id', 'name'];

            editModel(editUrl, editHeading, field)

            // Save
            saveBtn("{{ route('program-kerja.store') }}", myTable);

            // Delete
            var fitur = "Program Kerja";
            var editUrl = "{{ route('program-kerja.index') }}";
            var deleteUrl = "{{ route('program-kerja.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)
        });
    </script>
@endsection
