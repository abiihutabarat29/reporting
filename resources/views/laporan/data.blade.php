@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <form action="{{ route('laporan-kegiatan-pdf') }}" method="post">
        @csrf
        <div class="panel panel-inverse" data-sortable-id="ui-widget-1">
            <div class="panel-heading">
                <h4 class="panel-title">Filter</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <x-dropdownFilter name="filter_bidang" label="Bidang">
                                @foreach ($bidang as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </x-dropdownFilter>
                        </div>
                        <div class="col-md-5">
                            <x-dropdownFilter name="filter_program" label="Program Kerja">
                            </x-dropdownFilter>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-xs btn-warning form-control">Export
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <x-datatableExport link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th>Judul</th>
        <th style="width:15%">Bidang</th>
        <th style="width:15%">Program Kerja</th>
        <th style="width:15%">Kegiatan</th>
        <th style="width:10%">Tanggal</th>
        <th class="text-center" style="width:8%">Dokumentasi</th>
        <th class="text-center" style="width: 8%">Action</th>
    </x-datatableExport>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <x-input type="text" name="name" label="Judul Kegiatan"></x-input>
        <x-dropdown name="bidang_id" label="Bidang">
            @foreach ($bidang as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
            @endforeach
        </x-dropdown>
        <x-dropdown name="program_id" label="Program Kerja">
        </x-dropdown>
        <x-dropdown name="kegiatan_id" label="Kegiatan">
        </x-dropdown>
        <x-textarea name="description" label="Deskripsi" placeholder="Tuliskan deskripsi singkat...">
        </x-textarea>
        <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-9">
                <img class="img-rounded" id="Image" alt="Image" width='100'>
            </div>
        </div>
        <x-inputDoc type="file" name="foto" label="Dokumentasi"></x-inputDoc>
        <x-input type="date" name="date" label="Tanggal"></x-input>
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
                    url: '{{ route('laporan.index') }}',
                    data: function(d) {
                        d.bidang_id = $('#filter_bidang').val();
                        d.program_id = $('#filter_program').val();
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
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
            $(document).ready(function() {
                $('.popup-link').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });
            });
            // Create
            $("#create").click(function() {
                $("#saveBtn").val("create");
                $("#modelHeading").html("Tambah Laporan Kegiatan");
                $("#ajaxModel").modal("show");
                $("#delete").modal("show");
                $("#ajaxForm").trigger("reset");
                $("#hidden_id").val("");
                $("#Image").attr("src", "assets/img/images.png");
            });

            $(document).ready(function() {
                $("#foto").change(function() {
                    var input = this;
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#Image").attr("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                });
            });

            // Edit
            var editUrl = "{{ route('laporan.index') }}";
            var urlGetProgram = "{{ url('program/get-program') }}";
            var urlGetKegiatan = "{{ url('kegiatan/get-kegiatan') }}";
            var editHeading = "Edit Laporan Kegiatan";
            var field = ['name', 'bidang_id', 'program_id', 'kegiatan_id', 'description', 'date'];

            editModelLaporan(editUrl, editHeading, field, urlGetProgram, urlGetKegiatan)

            // Save
            saveImage("{{ route('laporan.store') }}", myTable);

            // Delete
            var fitur = "Laporan Kegiatan";
            var editUrl = "{{ route('laporan.index') }}";
            var deleteUrl = "{{ route('laporan.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)


            // $(document).ready(function() {
            //     var isProcessing = false;
            //     $("#export").on('click', function() {
            //         if (isProcessing) {
            //             return;
            //         }
            //         isProcessing = true;
            //         var originalButtonText = $(this).html();
            //         $(this).prop('disabled', true);
            //         $(this).html(
            //             "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> memproses...</i></span>"
            //         );
            //         var bidang_id = $('#filter_bidang').val();
            //         var program_id = $('#filter_program').val();

            //         $.ajax({
            //             url: '/laporan-kegiatan-pdf',
            //             type: 'POST', // Ubah ke metode POST
            //             data: {
            //                 bidang_id: bidang_id,
            //                 program_id: program_id,
            //             },
            //             xhrFields: {
            //                 responseType: 'blob'
            //             },
            //             success: function(response) {
            //                 var blob = new Blob([response], {
            //                     type: 'application/pdf'
            //                 });
            //                 var url = window.URL.createObjectURL(blob);
            //                 var a = document.createElement('a');
            //                 a.href = url;
            //                 a.download = 'file.pdf';
            //                 document.body.appendChild(a);
            //                 a.click();
            //                 window.URL.revokeObjectURL(url);
            //             },
            //             error: function() {
            //                 $("#export").html(originalButtonText);
            //                 isProcessing = false;
            //             }
            //         });
            //     });
            // });

            $('#bidang_id').on('change', function() {
                var idBidang = this.value;
                $("#program_id").html('');
                $('#kegiatan_id').html(
                    '<option value="">::Pilih Kegiatan::</option>');
                // const programDropdown = $('[name="program_id"]');
                // programDropdown.removeAttr('disabled');

                // const kegiatanDropdown = $('[name="kegiatan_id"]');
                // kegiatanDropdown.attr('disabled', 'disabled');

                $.ajax({
                    url: "{{ url('program/get-program') }}",
                    type: "POST",
                    data: {
                        bidang_id: idBidang,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result == "") {
                            $('#program_id').html(
                                '<option selected disabled>::Data Program Kerja tidak tersedia::</option>'
                            );
                        } else {
                            $('#program_id').html(
                                '<option selected disabled>::Pilih Program Kerja::</option>'
                            );
                        }
                        $.each(result, function(key, value) {
                            $("#program_id").append('<option value="' +
                                value
                                .id + '">' + value.name +
                                '</option>');
                        });
                    }
                });
            });

            $('#program_id').on('change', function() {
                var idProgram = this.value;
                $('#kegiatan_id').html(
                    '<option value="">::Pilih Kegiatan::</option>');
                // const kegiatanDropdown = $('[name="kegiatan_id"]');
                // kegiatanDropdown.removeAttr('disabled');
                $.ajax({
                    url: "{{ url('kegiatan/get-kegiatan') }}",
                    type: "POST",
                    data: {
                        program_id: idProgram,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result == "") {
                            $('#kegiatan_id').html(
                                '<option selected disabled>::Data Kegiatan tidak tersedia::</option>'
                            );
                        } else {
                            $('#kegiatan_id').html(
                                '<option selected disabled>::Pilih Kegiatan::</option>');
                        }
                        $.each(result, function(key, value) {
                            $("#kegiatan_id").append('<option value="' +
                                value
                                .id + '">' + value.name +
                                '</option>');
                        });
                    }
                });
            });
            $('#filter_bidang').on('change', function() {
                var idBidang = this.value;
                $('#filter_program').html(
                    '<option selected disabled>::Pilih Program::</option>');
                $.ajax({
                    url: "{{ url('program/get-program') }}",
                    type: "POST",
                    data: {
                        bidang_id: idBidang,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result == "") {
                            $('#filter_program').html(
                                '<option selected disabled>::Data Program Kerja tidak tersedia::</option>'
                            );
                        } else {
                            $('#filter_program').html(
                                '<option selected disabled>::Pilih Program Kerja::</option>'
                            );
                        }
                        $.each(result, function(key, value) {
                            $("#filter_program").append('<option value="' +
                                value
                                .id + '">' + value.name +
                                '</option>');
                        });
                        myTable.ajax.reload();
                    }
                });
            });
            $('#filter_program').on('change', function() {
                myTable.ajax.reload();
            });
        });
    </script>
@endsection
