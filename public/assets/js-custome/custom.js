function alertToastr(message) {
    $.gritter.add({
        title: "Berhasil!",
        text: message
    });
    return false
}
function alertToastrErr(message) {
    $.gritter.add({
        title: "Maaf!",
        text: message
    });
    return false
}

function alertDanger(message) {
    $("#alerts").html(
        '<div class="alert alert-danger alert-dismissible fade show ml-2 mr-2 mt-2">' +
        '<button type="button" class="close" data-dismiss="alert">' +
        "&times;</button><strong>Success! </strong>" +
        message +
        "</div>"
    );
    $(window).scrollTop(0);
    setTimeout(function () {
        $(".alert").alert("close");
    }, 5000);
}

$("#inputGroupFile01").change(function (event) {
    RecurFadeIn();
    readURL(this);
});
$("#inputGroupFile01").on("click", function (event) {
    RecurFadeIn();
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var filename = $("#inputGroupFile01").val();
        filename = filename.substring(filename.lastIndexOf("\\") + 1);
        reader.onload = function (e) {
            $("#preview").attr("src", e.target.result);
            $("#preview").hide();
            $("#preview").fadeIn(500);
            $(".custom-file-label").text(filename);
        };
        reader.readAsDataURL(input.files[0]);
    }
    $("#pleasewait").removeClass("loading").hide();
}

function RecurFadeIn() {
    FadeInAlert("Wait for it...");
}

function FadeInAlert(text) {
    $("#pleasewait").show();
    $("#pleasewait").text(text).addClass("loading");
}

function DataTable(ajaxUrl, columns, columnDefs) {
    var table = $(".table").DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 10,
        lengthMenu: [10, 50, 100, 200, 500],
        pageLength: 10, // Jumlah data per halaman default
        lengthChange: true,
        autoWidth: true,
        scrollCollapse: true,
        scrollX: true,
        paging: true,
        ajax: ajaxUrl,
        columns: columns,
        columnDefs: columnDefs,
    });

    return table;
}

function createModel(createHeading) {
    $("#create").click(function () {
        $("#saveBtn").val("create");
        $("#modelHeading").html(createHeading);
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
    });
}

function importModel(importHeading) {
    $("#import").click(function () {
        $("#saveBtn").val("import");
        $("#importHeading").html(importHeading);
        $("#importModel").modal("show");
        $("#importForm").trigger("reset");
    });
}

function editModel(editUrl, editHeading, field, urlGetDesa) {
    $("body").on("click", ".edit", function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var editId = $(this).data("id");
        $.get(editUrl + "/" + editId + "/edit", function (data) {
            $("#saveBtn").val("edit");
            $("#ajaxModel").modal("show");
            $("#hidden_id").val(data.id);
            $("#modelHeading").html(editHeading);
            $.each(field, function (index, value) {
                $("#" + value).val(data[value]);
            });
            var idKec = data.kecamatan_id;
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
                    url: urlGetDesa,
                    type: "POST",
                    data: {
                        kecamatan_id: idKec,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function (result) {
                        if (result == "") {
                            $('#desa_id').html(
                                '<option value="">::Data Desa/Kelurahan tidak tersedia::</option>'
                            );
                        } else {
                            $('#desa_id').html(
                                '<option value="">::Pilih Desa/Kelurahan::</option>');
                        }
                        $.each(result, function (key, value) {
                            $("#desa_id").append(
                                '<option value="' +
                                value
                                    .id + '">' + value.name +
                                '</option>');
                            $('#desa_id option[value=' +
                                value.id + ']').prop(
                                    'selected', true);
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
}

function saveBtn(urlStore, table) {
    $("#saveBtn").click(function (e) {
        e.preventDefault();
        $(this).html(
            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
        );

        $.ajax({
            data: $("#ajaxForm").serialize(),
            url: urlStore,
            type: "POST",
            dataType: "json",
            success: function (data) {
                if (data.errors) {
                    $(".alert-danger").html("");
                    $.each(data.errors, function (key, value) {
                        $(".alert-danger").show();
                        $(".alert-danger").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $(".alert-danger").fadeOut(5000);
                        $("#saveBtn").html("Simpan");
                    });
                } else {
                    table.draw();
                    alertToastr(data.success);
                    $("#saveBtn").html("Simpan");
                    $("#ajaxModel").modal("hide");
                }
            },
        });
    });
}

function saveImage(urlStore, table) {
    $("#saveBtn").click(function (e) {
        e.preventDefault();
        $(this).html(
            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
        );

        var form = $("#ajaxForm")[0]; // Ambil form element secara langsung
        var data = new FormData(form); // Gunakan FormData untuk mengirim data termasuk file

        $.ajax({
            data: data,
            url: urlStore,
            type: "POST",
            dataType: "json",
            contentType: false, // Set contentType ke false agar FormData dapat bekerja dengan benar
            processData: false, // Set processData ke false agar FormData dapat bekerja dengan benar
            success: function (data) {
                if (data.errors) {
                    $(".alert-danger").html("");
                    $.each(data.errors, function (key, value) {
                        $(".alert-danger").show();
                        $(".alert-danger").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $(".alert-danger").fadeOut(5000);
                        $("#saveBtn").html("Simpan");
                    });
                } else {
                    table.draw();
                    alertToastr(data.success);
                    $("#saveBtn").html("Simpan");
                    $("#ajaxModel").modal("hide");
                }
            },
        });
    });
}

function saveFile(urlStore, table) {
    $("#saveFile").click(function (e) {
        e.preventDefault();
        $(this).html(
            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
        );

        var form = $("#importForm")[0]; // Ambil form element secara langsung
        var data = new FormData(form); // Gunakan FormData untuk mengirim data termasuk file

        $.ajax({
            data: data,
            url: urlStore,
            type: "POST",
            dataType: "json",
            contentType: false, // Set contentType ke false agar FormData dapat bekerja dengan benar
            processData: false, // Set processData ke false agar FormData dapat bekerja dengan benar
            success: function (data) {
                if (data.errors) {
                    $(".alert-danger").html("");
                    $.each(data.errors, function (key, value) {
                        $(".alert-danger").show();
                        $(".alert-danger").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $(".alert-danger").fadeOut(5000);
                        $("#saveFile").html("Simpan");
                    });
                } else {
                    table.draw();
                    alertToastr(data.success);
                    $("#saveFile").html("Simpan");
                    $("#importModel").modal("hide");
                }
            },
        });
    });
}


function Delete(fitur, editUrl, deleteUrl, table) {
    $("body").on("click", ".delete", function () {
        var deleteId = $(this).data("id");
        $("#modelHeadingHps").html("Hapus");
        $("#fitur").html(fitur);
        $("#ajaxModelHps").modal("show");
        $.get(editUrl + "/" + deleteId + "/edit", function (data) {
            $("#field").html(data.name);
        });
        $("#hapusBtn").click(function (e) {
            e.preventDefault();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            $(this).html(
                "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menghapus...</i></span>"
            );
            $.ajax({
                type: "DELETE",
                url: deleteUrl + "/" + deleteId,
                data: {
                    _token: csrfToken,
                },
                success: function (data) {
                    if (data.errors) {
                        $(".alert-danger").html("");
                        $.each(data.errors, function (key, value) {
                            $(".alert-danger").show();
                            $(".alert-danger").append(
                                "<strong><li>" + value + "</li></strong>"
                            );
                            $(".alert-danger").fadeOut(5000);
                            $("#hapusBtn").html(
                                "<i class='fa fa-trash'></i>Hapus"
                            );
                        });
                    } else {
                        if (table) {
                            table.draw();
                        }
                        alertToastr(data.success);
                        $("#hapusBtn").html("<i class='fa fa-trash'></i>Hapus");
                        $("#ajaxModelHps").modal("hide");
                    }
                },
            });
        });
    });
}
