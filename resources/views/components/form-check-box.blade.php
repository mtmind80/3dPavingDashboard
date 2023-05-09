<div {{ $attributes->merge(["class"=>"form-check "]) }}>
    <input type="checkbox" class="form-check-input" name="{{ $name }}" value="{{ $value }}" id="{{ $id }}"{{ $checked ? ' checked' : '' }}>
    <label class="form-check-label" for="{{ $id }}">
        {{ $slot }}
    </label>
</div>
