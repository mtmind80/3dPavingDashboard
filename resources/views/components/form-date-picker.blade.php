@if (!empty($params['label']))
    <span class="form-field-label">{{ $params['label'] }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
@endif
<label for="{{ !empty($params['id']) ? $params['id'] : $name }}" class="field prepend-icon {{ $params['class'] ?? '' }}">
    <input type="text"
        @if (!empty($params['value']))
            @if ($params['value'] instanceof \Carbon\Carbon)
                value="{{ $params['value']->format(!empty($params['language']) && $params['language'] === 'es' ? 'm/d/Y' : 'm/d/Y') }}"
            @else
                value="{{ $params['value']->format('m/d/Y')}}"
            @endif
        @else
            value=""
        @endif
        class="{{ 'gui-input bootstrap-date-picker'. (!empty($params['language']) && $params['language'] === 'es' ? '-es' : '') . (!empty($params['class']) ? ' '.$params['class'] : '') }}"
        id="{{ !empty($params['id']) ? $params['id'] : $name }}"
        name="{{ $name }}"
        placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}"
        @if (!empty($params['attributes']))
            @foreach ($params['attributes'] as $key => $value)
                {{ ' ' . $key .'="'. $value .'"' }}
            @endforeach
        @endif
    >
    <span class="field-icon"><i class="far fa-calendar-alt"></i></span>
</label>

@push('components-styles')
    <link href="{{ $siteUrl }}/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
@endpush

@push('components-scripts')
    <script src="{{ $siteUrl }}/js/moment.min.js"></script>
    <script src="{{ $siteUrl }}/js/bootstrap-datetimepicker.min.js"></script>
    @if (!empty($params['language']) && $params['language'] === 'es')
        <script src="{{ $siteUrl }}/js/bootstrap-datetimepicker.es.js"></script>
    @endif
    <script>
        $(document).ready(function(){
            //$('.bootstrap-datetime-picker').datetimepicker();

            $('.bootstrap-date-picker').datetimepicker({
                pickTime: false
            });

/*
            $('.bootstrap-datetime-picker-es').datetimepicker({
                format  : 'DD-MM-YYYY h:mm A',
                language: 'es'
            });
            $('.bootstrap-date-picker-es').datetimepicker({
                pickTime: false,
                format  : 'DD-MM-YYYY',
                language: 'es'
            });
*/
        });
    </script>
@endpush
