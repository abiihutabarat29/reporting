@props(['type', 'name', 'label'])
<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}</label>
    <div class="col-md-9">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-control']) }} accept=".jpg,.jpeg,.png" />
        <small><i>* Opsional.</i></small>
    </div>
</div>
