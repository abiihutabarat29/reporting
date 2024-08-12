@props(['name', 'label', 'placeholder'])
<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}<span class="text-danger">*</span></label>
    <div class="col-md-9">
        <textarea name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}" rows="3"
            {{ $attributes->merge(['class' => 'form-control']) }}></textarea>
    </div>
</div>
