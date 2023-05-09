@if (empty($params['label']) || $params['label'] != 'none' )
    <span class="form-field-label">{!! $params['label'] ?? ucfirst($name) !!}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : "@lang('translation.field_required')") .'"></i>' : '' !!}</span>
@endif
<label for="{{ $id }}" class="field{{ empty($params['iconClass']) || $params['iconClass'] != 'none' ? ' prepend-icon' : '' }} ">
    <input type="text"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ $slot ?? null }}"
           {{ $attributes->merge(["class"=>"form-control gui-input "]) }}
           >
    @if (empty($params['iconClass']) || $params['iconClass'] != 'none')
        <span class="field-icon"><i class="{{ $params['iconClass'] ?? 'fas fa-sticky-note' }}"></i></span>
    @endif
</label>
