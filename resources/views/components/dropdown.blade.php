@props(['name', 'label'])
<div class="form-group">
    <label class="col-md-3 control-label">{{ $label }}<span class="text-danger">*</span></label>
    <div class="col-md-9">
        <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control']) }}>
            <option selected disabled>::Pilih {{ $label }}::</option>
            {{ $slot }}
        </select>
    </div>
</div>
