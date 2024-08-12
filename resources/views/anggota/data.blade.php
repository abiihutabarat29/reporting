@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}"></x-headerLink>
    <x-datatable link="javascript:void(0)" submenu="{{ $submenu }}" label="Tambah">
        <th style="width:5%">No</th>
        <th>Nama</th>
        <th style="width:20%">Email</th>
        <th style="width:10%">No. HP</th>
        <th class="text-center" style="width:10%">SK</th>
        <th class="text-center" style="width: 15%">Action</th>
    </x-datatable>
@endsection
@section('modal')
    <x-ajaxModel size="">
        <x-input type="text" name="name" label="Nama Anggota"></x-input>
        <x-input type="email" name="email" label="Email Anggota"></x-input>
        <x-input type="number" name="nohp" label="No. HP Anggota"></x-input>
        <x-inputFile type="file" name="sk" label="Upload File SK"></x-inputFile>
    </x-ajaxModel>
    <x-delete></x-delete>
    <x-imageModal></x-imageModal>
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
                pageLength: 10,
                lengthMenu: [10, 50, 100, 200, 500],
                lengthChange: true,
                autoWidth: true,
                scrollCollapse: true,
                paging: true,
                ajax: {
                    url: '{{ route('anggota.index') }}'
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
                $("#modelHeading").html("Tambah Anggota");
                $("#ajaxModel").modal("show");
                $("#delete").modal("show");
                $("#ajaxForm").trigger("reset");
                $("#hidden_id").val("");
                // $("#ImageSk").attr("src", "assets/img/images.png");
            });

            // $(document).ready(function() {
            //     $('body').on('click touchstart', 'a.view-sk', function(event) {
            //         event.preventDefault();
            //         var skId = $(this).data(
            //             'sk-id');
            //         $.ajax({
            //             url: '/get-sk-image',
            //             method: 'GET',
            //             data: {
            //                 sk_id: skId
            //             },
            //             success: function(response) {
            //                 if (response.imagePath) {
            //                     var imagePath = response.imagePath;
            //                     $('#modalImage').attr('src', imagePath);
            //                     $('#imageModal').modal('show');
            //                 }
            //             }
            //         });
            //     });
            // });

            // Edit
            var field = ['name',
                'email', 'nohp',
            ];

            $("body").on("click", ".edit", function() {
                var editId = $(this).data("id");
                $.get("{{ route('anggota.index') }}" + "/" + editId + "/edit", function(data) {
                    $("#saveBtn").val("edit");
                    $("#ajaxModel").modal("show");
                    $("#hidden_id").val(data.id);
                    $("#modelHeading").html("Edit Anggota");
                    $.each(field, function(index, value) {
                        $("#" + value).val(data[value]);
                    });
                    // var imageSk = data.sk;
                    // if (imageSk) {
                    //     var imageUrl =
                    //         "/storage/foto-sk/" +
                    //         imageSk;
                    //     $("#ImageSk").attr("src",
                    //         imageUrl);
                    // } else {
                    //     $("#ImageSk").attr("src",
                    //         "/assets/img/images.png"
                    //     );
                    // }
                });
            });

            // Save
            saveImage("{{ route('anggota.store') }}", myTable);

            // Delete
            var fitur = "Anggota";
            var editUrl = "{{ route('anggota.index') }}";
            var deleteUrl = "{{ route('anggota.store') }}";
            Delete(fitur, editUrl, deleteUrl, myTable)
        });

        var dokumenUrl = "{{ url('get-sk') }}";
        var dokumenPath = "{{ asset('storage/sk') }}";
        Review(dokumenUrl, dokumenPath);
    </script>
@endsection
