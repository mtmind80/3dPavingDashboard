<div {{ $attributes->merge(["class"=>"show-container "]) }}>
    @if (!empty($params['label']) && $params['label'] != 'none')
        <span class="form-field-label">{!! $params['label'] !!}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : "@lang('translation.field_required')") .'"></i>' : '' !!}</span>
    @endif
    <div class="form-field-value{{ !empty($params['iconClass']) ? ' prepend-icon' : '' }}">
        <span class="field-value"{!! !empty($params['id']) ? ' id="'.$params['id'].'"' : '' !!}>{{ $slot ?? null }}</span>
        @if (!empty($params['iconClass']))
            <span class="field-icon"><i class="{{ $params['iconClass'] }}"></i></span>
        @endif
    </div>
</div>
