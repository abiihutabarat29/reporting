<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}<span class="text-danger optionSpan">*</span></label>
    <div class="col-md-9">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-control']) }} />
        <small class="optionSmall"><i>* Opsional.</i></small>
    </div>
</div>
