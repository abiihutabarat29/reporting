@props(['submenu', 'label'])
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:void(0)" id="create" class="btn btn-xs btn-white" data-toggle="modal"><i
                            class="fa fa-plus"></i>
                        {{ $label }}</a>
                </div>
                <h4 class="panel-title">{{ $submenu }}</h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered" id="datatable">
                    <thead>
                        <tr>
                            {{ $slot }}
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
