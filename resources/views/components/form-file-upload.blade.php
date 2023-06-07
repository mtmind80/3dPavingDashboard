<div {{ $attributes->merge(["class"=>"upload-file-widget "]) }} data-lang="{{ $lang ?? 'en' }}">
    @if (empty($params['label']) || $params['label'] != 'none' )
        <span class="form-field-label">{{ $params['label'] ?? ucwords(str_replace('_', ' ', $name)) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}<a href="javascript:" class="ml8 remove-file-link hidden" data-toggle="tooltip" title="{{ !is_null($lang) && $lang === 'es' ? 'Remover' : 'Clear' }}"><i class="fa fa-times"></i></a></span>
    @endif
    <label class="prepend-icon append-button field file">
        <span class="button btn-primary">{!! $params['buttonLabel'] ?? (!is_null($lang) && $lang === 'es' ? 'Seleccionar' : 'Browse') !!}</span>
        <input type="file" class="gui-file" name="{{ $name }}" id="{{ $id }}">
        <span class="field">
            <input type="text"
                class="gui-input mt0 {{ $params['paddingLeft'] ?? 'pl100' }}"
                placeholder="{!! $params['placeholder'] ?? (!is_null($lang) && $lang === 'es' ? 'Subir fichero' : 'Upload file') !!}"
            >
        </span>
        <label class="field-icon"><i class="{{ $params['iconClass'] ?? 'fa fa-upload' }}"></i></label>
        <input type="hidden" class="hidden-file-name" name="hidden_{{ $name }}" id="hidden_{{ $name }}" value="{{ $slot ?? null }}">
    </label>
</div>


