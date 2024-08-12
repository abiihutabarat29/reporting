@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th style="width:5%">Foto</th>
        <th>Nama</th>
        <th style="width:18%">Email</th>
        <th style="width:10%">No. HP</th>
        <th style="width:15%">Kecamatan</th>
        <th style="width:15%">Desa/Kelurahan</th>
        <th class="text-center" style="width:10%">Level</th>
        <th class="text-center" style="width: 8%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <div class="form-group">
            <div class="col-md-12">
                <img class="img-rounded" id="profileImage" alt="Profile Image" width='100'>
            </div>
        </div>
        <x-dropdown name="role" label="Level">
            <option value="1">Administrator</option>
            <option value="2">Admin Kecamatan</option>
            <option value="3">Admin Desa</option>
        </x-dropdown>
        <div class="form-group" id="kecamatan">
            <label class="col-md-3 control-label">Kecamatan<span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="kecamatan_id" name="kecamatan_id">
                    <option selected disabled>::Pilih Kecamatan::</option>
                    @foreach ($kecamatan as $data)
                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" id="desa">
            <label class="col-md-3 control-label">Desa<span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="desa_id" name="desa_id">
                    <option selected disabled>::Pilih Desa::</option>
                </select>
            </div>
        </div>
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
                    url: '{{ route('user.index') }}'
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
                        data: "kecamatan",
                        name: "kecamatan",
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

            $("#create").click(function() {
                $("#saveBtn").val("create");
                $("#modelHeading").html("Tambah User");
                $("#ajaxModel").modal("show");
                $("#delete").modal("show");
                $("#ajaxForm").trigger("reset");
                $("#hidden_id").val("");
                $("#profileImage").attr("src", "assets/img/blank.jpg");
                var hiddenIdValue = $("#hidden_id").val();
                if (hiddenIdValue) {
                    $(".optionSpan").hide();
                    $(".optionSmall").show();
                } else {
                    $(".optionSpan").show();
                    $(".optionSmall").hide();
                }
                $('#kecamatan').hide()
                $('#desa').hide()
                $('#role').on('change', function() {
                    var roleId = this.value;
                    if (roleId == 1) {
                        $('#kecamatan').hide()
                        $('#desa').hide()
                    } else if (roleId == 2) {
                        $('#kecamatan').show()
                        $('#desa').hide()
                    } else {
                        $('#kecamatan').show()
                        $('#desa').show()
                    }
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
            var field = ['kecamatan_id', 'name',
                'email', 'nohp', 'role'
            ];

            $("body").on("click", ".edit", function() {
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var editId = $(this).data("id");
                $.get("{{ route('user.index') }}" + "/" + editId + "/edit", function(data) {
                    $("#saveBtn").val("edit");
                    $("#ajaxModel").modal("show");
                    $("#hidden_id").val(data.id);
                    $("#modelHeading").html("Edit User");
                    $.each(field, function(index, value) {
                        $("#" + value).val(data[value]);
                    });
                    if (data.role == 1) {
                        $('#kecamatan').hide()
                        $('#desa').hide()
                    } else if (data.role == 2) {
                        $('#kecamatan').show()
                        $('#desa').hide()
                    } else {
                        $('#kecamatan').show()
                        $('#desa').show()
                    }
                    $('#role').on('change', function() {
                        var roleId = this.value;
                        if (roleId == 1) {
                            $('#kecamatan').hide()
                            $('#desa').hide()
                        } else if (roleId == 2) {
                            $('#kecamatan').show()
                            $('#desa').hide()
                        } else {
                            $('#kecamatan').show()
                            $('#desa').show()
                        }
                    });
                    var idKec = data.kecamatan_id;
                    var idDesa = data.desa_id;
                    var imageName = data.foto;
                    if (imageName) {
                        var imageUrl =
                            "/storage/foto-users/" +
                            imageName;
                        $("#profileImage").attr("src",
                            imageUrl);
                    } else {
                        $("#profileImage").attr("src",
                            "/assets/img/blank.jpg"
                        );
                    }
                    if (idKec) {
                        $.ajax({
                            url: "{{ url('desa/get-desa') }}",
                            type: "POST",
                            data: {
                                kecamatan_id: idKec,
                                _token: csrfToken
                            },
                            dataType: 'json',
                            success: function(result) {
                                if (result == "") {
                                    $('#desa_id').html(
                                        '<option value="">::Data Desa/Kelurahan tidak tersedia::</option>'
                                    );
                                } else {
                                    $('#desa_id').html(
                                        '<option value="">::Pilih Desa/Kelurahan::</option>'
                                    );
                                }
                                $.each(result, function(key, value) {
                                    $("#desa_id").append(
                                        '<option value="' +
                                        value
                                        .id + '">' + value.name +
                                        '</option>');

                                    if (value.id == idDesa) {
                                        $('#desa_id option[value=' + value
                                            .id + ']').prop('selected',
                                            true);
                                    }
                                });
                            }
                        });
                    } else {
                        console.log('ID Kecamatan tidak ada.');
                    }
                    var hiddenIdValue = $("#hidden_id").val();
                    if (hiddenIdValue) {
                        $(".optionSpan").hide();
                        $(".optionSmall").show();
                    } else {
                        $(".optionSpan").show();
                        $(".optionSmall").hide();
                    }
                });
            });

            // Save
            saveImage("{{ route('user.store') }}", myTable);

            // Delete
            var fitur = "User Desa/Kelurahan";
            var editUrl = "{{ route('user.index') }}";
            var deleteUrl = "{{ route('user.store') }}";
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
