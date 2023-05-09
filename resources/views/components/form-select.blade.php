@if (!empty($params['label']))
    <span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : "@lang('translation.field_required')") .'"></i>' : '' !!}</span>
@endif
<label class="field select{{ empty($params['iconClass']) || $params['iconClass'] !== 'none' ? ' prepend-icon' : ''}}">
    <select name="{{ $name }}"
            id="{{ !empty($params['id']) ? $params['id'] : $name }}"
            value="{{ $params['value'] ?? null }}"
            class="form-control grayed {{ $params['class'] ?? '' }}"
            @if (!empty($params['attributes']))
                @foreach ($params['attributes'] as $key => $value)
                    {{ ' ' . $key .'='. $value }}
                @endforeach
            @endif
    >
        @foreach ($items as $key => $value)
            <option value="{{ $key }}"{{ $key == $selected ? ' selected' : ''}} {{ empty($key) ? ' disabled' : ''}}>{{ $value }}</option>
        @endforeach
    </select>
    <i class="arrow double"></i>
    @if (empty($params['iconClass']) || $params['iconClass'] !== 'none')
        <span class="field-icon"><i class="{{ $params['iconClass'] ?? 'fas fa-indent' }}"></i></span>
    @endif
</label>
