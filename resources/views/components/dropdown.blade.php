@props(['name', 'label'])
<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}<span class="text-danger">*</span></label>
    <div class="col-md-9">
        <select class="default-select2 form-control" id="{{ $name }}" name="{{ $name }}">
            <option selected disabled>::Pilih {{ $label }}::</option>
            {{ $slot }}
        </select>
    </div>
</div>
