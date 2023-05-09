@if (empty($params['label']) || $params['label'] != 'none' )
    <span class="form-field-label mb8">{!! $params['label'] ?? ucfirst($name) !!}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : "@lang('translation.field_required')") .'"></i>' : '' !!}</span>
@endif
<div
    {{ $attributes->merge(["class" => "radio-container d-flex w-full ".($isHorz ? 'flex-row' : 'flex-column')." "]) }}
    {{ !empty($params['label']) ? 'id='.$params['label'] : '' }}
>
    @foreach ($radios as $radio)
        <label class="radio-item {{ $isHorz ? 'd-inline-flex' : 'd-flex mb-2' }} flex-row justify-content-between align-items-center gap-6 mr-5 flex">
            <input
                type="radio"
                name="gender"
                value="{{ $radio['value'] ?? '' }}"
                {{ !empty($radio['checked']) ? 'checked' : '' }}
            >
            <i class="unchecked far fa-circle"></i>
            <i class="checked far fa-dot-circle"></i>
            <span class="flex-grow-1">{!! $radio['label'] !!}</span>
        </label>
    @endforeach
</div>

@push('components-styles')
    <link href="{{ $siteUrl }}/css/component-radio-group.css" rel="stylesheet" type="text/css" />
@endpush
