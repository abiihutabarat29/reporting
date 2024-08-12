@props(['name', 'label'])
<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}</label>
    <div class="col-md-9">
        <select id="{{ $name }}" name="{{ $name }}"
            {{ $attributes->merge(['class' => 'default-select2 form-control']) }}>
            <option selected disabled>::Pilih {{ $label }}::</option>
            {{ $slot }}
        </select>
    </div>
</div>
