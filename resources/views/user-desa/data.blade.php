@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th style="width:5%">Foto</th>
        <th style="width:25%">Nama</th>
        <th style="width:18%">Email</th>
        <th style="width:10%">No. HP</th>
        <th style="width:15%">Desa/Kelurahan</th>
        <th class="text-center" style="width:10%">Level</th>
        <th class="text-center" style="width: 15%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <div class="form-group">
            <div class="col-md-12">
                <img class="img-rounded" id="profileImage" alt="Profile Image" width='100'>
            </div>
        </div>
        <x-dropdown name="kecamatan_id" label="Kecamatan">
            @foreach ($kecamatan as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
            @endforeach
        </x-dropdown>
        <x-dropdown name="desa_id" label="Desa/Kelurahan">
        </x-dropdown>
        <x-input type="text" name="name" label="Nama Operator"></x-input>
        <x-input type="email" name="email" label="Email Operator"></x-input>
        <x-input type="number" name="nohp" label="No. HP Operator"></x-input>
        <x-inputFoto type="file" name="foto" label="Upload Foto"></x-inputFoto>
        <x-inputPassword type="password" name="password" label="Password"></x-inputPassword>
        <x-inputPassword type="password" name="repassword" label="Re-Password"></x-inputPassword>
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
                    url: '{{ route('user-desa.index') }}'
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                    },
                    {
                        data: "foto",
                        name: "foto",
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
                        data: "desa",
                        name: "desa",
                    },
                    {
                        data: "role",
                        name: "role",
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
            var createHeading = "Tambah User Desa/Kelurahan";
            createModel(createHeading)
            $(document).ready(function() {
                // Handle image selection and preview
                $("#foto").change(function() {
                    var input = this;
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#profileImage").attr("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                });
            });

            // Edit
            var editUrl = "{{ route('user-desa.index') }}";
            var urlGetDesa = "{{ url('desa/get-desa') }}";
            var editHeading = "Edit User Desa/Kelurahan";
            var field = ['kecamatan_id', 'desa_id', 'name',
                'email', 'nohp'
            ];

            editModel(editUrl, editHeading, field, urlGetDesa)

            // Save
            saveImage("{{ route('user-desa.store') }}", myTable);

            // Delete
            var fitur = "User Desa/Kelurahan";
            var editUrl = "{{ route('user-desa.index') }}";
            var deleteUrl = "{{ route('user-desa.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)

            $('#kecamatan_id').on('change', function() {
                var idKec = this.value;
                $("#desa_id").html('');
                $.ajax({
                    url: "{{ url('desa/get-desa') }}",
                    type: "POST",
                    data: {
                        kecamatan_id: idKec,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result == "") {
                            $('#desa_id').html(
                                '<option value="">::Data Desa/Kelurahan tidak tersedia::</option>'
                            );
                        } else {
                            $('#desa_id').html(
                                '<option value="">::Pilih Desa/Kelurahan::</option>');
                        }
                        $.each(result, function(key, value) {
                            $("#desa_id").append('<option value="' +
                                value
                                .id + '">' + value.name +
                                '</option>');
                        });
                    }
                });
            });
        });
    </script>
@endsection
