@if (!empty($params['maxChars']))
    <div class="char-counter" data-max="{{ $params['maxChars'] }}">
        <span class="counter-container">Left: <span class="counter"></span></span>
@endif
        @if (empty($params['label']) || $params['label'] != 'none' )
            <span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucwords(str_replace('_', ' ', $name)) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : "@lang('translation.field_required')") .'"></i>' : '' !!}</span>
        @endif
        <label for="{{ $id }}" class="field prepend-icon">
            <textarea
                name="{{ $name }}"
                id="{{ $id }}"
                {{ $attributes->merge(["class"=>"form-control gui-textarea "]) }}
            >{{ $slot ?? null }}</textarea>
            @if (empty($params['iconClass']) || $params['iconClass'] != 'none' )
                <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-sticky-note-o' }}"></i></span>
            @endif
        </label>
@if (!empty($params['maxChars']))
    </div>
@endif
