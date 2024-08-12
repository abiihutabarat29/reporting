@props(['type', 'name', 'label'])
<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}<span class="text-danger">*</span></label>
    <div class="col-md-9">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-control']) }} accept=".jpg,.jpeg,.png" />
        <small><i class="fa fa-info-circle"></i><i> Ukuran foto dokumentasi maksimal 2MB</i></small>
    </div>
</div>
