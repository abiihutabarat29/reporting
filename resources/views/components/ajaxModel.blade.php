@props(['size'])
<div class="modal fade" id="ajaxModel">
    <div class="modal-dialog {{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <form id="ajaxForm" name="ajaxForm" class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @csrf
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success" id="saveBtn" value="create">Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
