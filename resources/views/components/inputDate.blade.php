@props(['type', 'name', 'label'])
<div class="form-group">
    <label class="col-md-2 control-label">{{ $label }}</label>
    <div class="col-md-9">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-control']) }} />
    </div>
</div>
