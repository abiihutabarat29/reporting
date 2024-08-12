@props(['submenu'])
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
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
