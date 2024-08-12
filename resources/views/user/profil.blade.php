@extends('layouts.app')
@section('content')
    <x-headerLink menu="{{ $menu }}" link="{{ route('dashboard') }}">
    </x-headerLink>
    @if (!$user || !$user->profil->profilIsComplete())
        <div class="profile-container mb-5">
            <div class="card bg-white rounded-lg" style="display: flex; justify-content: center; align-items: center;">
                <div class="block">
                    <h4 class="text-center text-danger font-weight-bold"><i class="fa fa-info-circle"></i> Mohon lengkapi
                        profil anda sebelum membuat <b>Laporan Kegiatan</b>.
                        </h6>
                </div>
            </div>
        </div>
    @endif
    <div class="profile-container">
        <div class="profile-section">
            <div class="profile-left">
                <div class="profile-image">
                    @if (Auth::user()->foto == null)
                        <img src="{{ url('assets/img/blank.jpg') }}">
                    @else
                        <a href="{{ url('storage/foto-users/' . Auth::user()->foto) }}" class="popup-link"
                            target="blank"><img src="{{ url('storage/foto-users/' . Auth::user()->foto) }}"
                                alt="{{ Auth::user()->name }}">
                        </a>
                    @endif
                    <i class="fa fa-user hide"></i>
                </div>
                <div class="m-b-10">
                    <a href="javascript:void(0)" id="updatefoto" class="btn btn-warning btn-block btn-sm"
                        data-toggle="modal">Ganti Foto</a>
                    <a href="javascript:void(0)" id="updatepass" class="btn btn-warning btn-block btn-sm"
                        data-toggle="modal">Ganti Password</a>
                    <a href="javascript:void(0)" id="updateprofil" class="btn btn-warning btn-block btn-sm"
                        data-toggle="modal">Perbaharui Profil</a>
                    @if (Auth::user()->role == 2 || Auth::user()->role == 3)
                        <a href="javascript:void(0)" id="updatebanner" class="btn btn-warning btn-block btn-sm"
                            data-toggle="modal">Perbaharui Banner</a>
                    @endif
                </div>
            </div>
            <div class="profile-right">
                <div class="profile-info">
                    <div class="table-responsive">
                        <table class="table table-profile">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>
                                        <h4>{{ $user->name }}
                                            @if ($user->role == 1)
                                                <small>administrator kabupaten</small>
                                            @elseif ($user->role == 2)
                                                <small>administrator kecamatan</small>
                                            @elseif ($user->role == 3)
                                                <small>administrator desa/kelurahan</small>
                                            @endif
                                        </h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="highlight">
                                    <td class="field">Organisasi</td>
                                    @if ($user->profil->nama_pkk == null)
                                        <td><span class="text-danger"><i>* tidak ada</i></span></td>
                                    @else
                                        <td>{{ $user->profil->nama_pkk }}</td>
                                    @endif
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="highlight">
                                    <td class="field">HP Kantor</td>
                                    @if ($user->profil->nohp_kantor == null)
                                        <td><span class="text-danger"><i>* tidak ada</i></span></td>
                                    @else
                                        <td>{{ $user->profil->nohp_kantor }}</td>
                                    @endif
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="highlight">
                                    <td class="field">Alamat Kantor</td>
                                    @if ($user->profil->alamat_kantor == null)
                                        <td><span class="text-danger"><i>* tidak ada</i></span></td>
                                    @else
                                        <td>{{ $user->profil->alamat_kantor }}</td>
                                    @endif
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="highlight">
                                    <td class="field">Email Admin</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="highlight">
                                    <td class="field">HP Admin</td>
                                    @if ($user->nohp == null)
                                        <td><span class="text-danger"><i>* tidak ada</i></span></td>
                                    @else
                                        <td>{{ $user->nohp }}</td>
                                    @endif
                                </tr>
                                <tr class="divider">
                                    <td colspan="2"></td>
                                </tr>
                                @if (Auth::user()->role == 2 || Auth::user()->role == 3)
                                    <tr class="highlight">
                                        <td class="field">Banner</td>
                                        @if ($user->profil->banner == null)
                                            <td><img src="assets/img/images.png" alt="Banner" width="500"></td>
                                        @else
                                            <td><img src="{{ asset('storage/foto-banner/' . $user->profil->banner) }}"
                                                    alt="Banner" width="100%"></td>
                                        @endif
                                    </tr>
                                    <tr class="divider">
                                        <td colspan="2"></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="ajaxModelFoto{{ $user->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <form id="fotoForm" name="fotoForm" class="form-horizontal" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @csrf
                        @method('put')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Pilih Foto<span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="file" name="foto" id="foto" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="updateBtn" value="update">Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModelBanner{{ $user->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modelHeadingBanner"></h4>
                </div>
                <form id="bannerForm" name="bannerForm" class="form-horizontal" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @csrf
                        @method('put')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Banner<span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="file" name="banner" id="banner" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="updateBannerBtn" value="update">Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModelPass{{ $user->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modelHeadingPass"></h4>
                </div>
                <form id="passForm" name="passForm" class="form-horizontal">
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @csrf
                        @method('put')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Password Lama<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="password" name="old_password" id="old_password" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Password Baru<span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="password" name="password" id="password" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Konfirmasi Password<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="password" name="repassword" id="repassword" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="updatePassBtn"
                            value="update-password">Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModelProfil">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modelHeadingProfil"></h4>
                </div>
                <form id="profilForm" name="profilForm" class="form-horizontal">
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label class="col-md-4 control-label">Organisasi</label>
                            <div class="col-md-8">
                                <input type="text" name="nama_pkk" id="nama_pkk" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">HP Kantor</label>
                            <div class="col-md-8">
                                <input type="number" name="nohp_kantor" id="nohp_kantor" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Alamat Kantor</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="alamat_kantor" id="alamat_kantor" placeholder="Tuliskan Alamat..."
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success" id="updateProfilBtn">Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $("#updatefoto").click(function(e) {
                e.preventDefault();
                $("#modelHeading").html("Ganti Foto Profil");
                $("#ajaxModelFoto{{ $user->id }}").modal("show");
            });
            $("#updatebanner").click(function(e) {
                e.preventDefault();
                $("#modelHeadingBanner").html("Ganti Banner");
                $("#ajaxModelBanner{{ $user->id }}").modal("show");
            });

            $("#updateBtn").click(function(e) {
                e.preventDefault();
                $(this).html(
                    "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                );
                let formData = new FormData($("#fotoForm")[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('profil.update.foto', ['id' => $user->id]) }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.errors) {
                            $(".alert-danger").html("");
                            $.each(response.errors, function(key, value) {
                                $(".alert-danger").show();
                                $(".alert-danger").append(
                                    "<strong><li>" + value + "</li></strong>"
                                );
                                $(".alert-danger").fadeOut(5000);
                                $("#updateBtn").html("Update");
                            });
                        } else {
                            alertToastr(response.success);
                            $("#updateBtn").html("Update");
                            $("#ajaxModelFoto{{ $user->id }}").modal("hide");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    },
                });
            });
            $("#updateBannerBtn").click(function(e) {
                e.preventDefault();
                $(this).html(
                    "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                );
                let formData = new FormData($("#bannerForm")[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('profil.update.banner', ['id' => $user->id]) }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.errors) {
                            $(".alert-danger").html("");
                            $.each(response.errors, function(key, value) {
                                $(".alert-danger").show();
                                $(".alert-danger").append(
                                    "<strong><li>" + value + "</li></strong>"
                                );
                                $(".alert-danger").fadeOut(5000);
                                $("#updateBannerBtn").html("Update");
                            });
                        } else {
                            alertToastr(response.success);
                            $("#updateBannerBtn").html("Update");
                            $("#ajaxModelBanner{{ $user->id }}").modal("hide");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    },
                });
            });
            $("#updatepass").click(function(e) {
                e.preventDefault();
                $("#modelHeadingPass").html("Ganti Password");
                $("#ajaxModelPass{{ $user->id }}").modal("show");
            });

            $("#updatePassBtn").click(function(e) {
                e.preventDefault();
                $(this).html(
                    "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                );

                let formData = new FormData($("#passForm")[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('profil.update.password', ['id' => $user->id]) }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.errors) {
                            $(".alert-danger").html("");
                            $.each(response.errors, function(key, value) {
                                $(".alert-danger").show();
                                $(".alert-danger").append(
                                    "<strong><li>" + value + "</li></strong>"
                                );
                                $(".alert-danger").fadeOut(5000);
                                $("#updatePassBtn").html("Update");
                            });
                        } else {
                            alertToastr(response.success);
                            $("#updatePassBtn").html("Update");
                            $("#ajaxModelPass{{ $user->id }}").modal("hide");
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorResponse = JSON.parse(xhr.responseText);
                        alertToastrErr(errorResponse.error);
                        $("#updatePassBtn").html("Update");
                    },
                });
            });
            $("#updateprofil").click(function() {
                $.get("{{ route('profil.edit') }}", function(data) {
                    $("#ajaxModelProfil").modal("show");
                    $("#modelHeadingProfil").html("Perbaharui Profil");
                    $("#nama_pkk").val(data.nama_pkk);
                    $("#nohp_kantor").val(data.nohp_kantor);
                    $("#alamat_kantor").val(data.alamat_kantor);
                });
            });
            $("#updateProfilBtn").click(function(e) {
                e.preventDefault();
                $(this).html(
                    "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                );

                let formData = new FormData($("#profilForm")[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('profil.update.profil', ['id' => $user->id]) }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.errors) {
                            $(".alert-danger").html("");
                            $.each(response.errors, function(key, value) {
                                $(".alert-danger").show();
                                $(".alert-danger").append(
                                    "<strong><li>" + value + "</li></strong>"
                                );
                                $(".alert-danger").fadeOut(5000);
                                $("#updateProfilBtn").html("Update");
                            });
                        } else {
                            alertToastr(response.success);
                            $("#updateProfilBtn").html("Update");
                            $("#ajaxModelProfil").modal("hide");
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorResponse = JSON.parse(xhr.responseText);
                        alertToastrErr(errorResponse.error);
                        $("#updateProfilBtn").html("Update");
                    },
                });
            });
        });
    </script>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.popup-link').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: false
                }
            });
        });
    </script>
@endsection
