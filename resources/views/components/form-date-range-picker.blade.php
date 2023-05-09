@if (!empty($params['label']))
    <span class="form-field-label">{{ $params['label'] }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
@endif

<label for="{{ !empty($params['id']) ? $params['id'] : $from .'_'. $to }}" class="field prepend-icon">
    <input
        {{ $attributes->merge(["class" => "gui-input bootstrap-daterangepicker bootstrap-daterange-picker ".(!empty($params['language']) && $params['language'] === 'es' ? '-es' : '')." "]) }}
        type="text"
        id="{{ !empty($params['id']) ? $params['id'] : $from .'_'. $to }}"
        placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}"
        data-from_id="{{ $from }}"
        data-to_id="{{ $to }}"
        @if (!empty($params['min_date']))
            data-min_date="{{ $params['min_date'] }}"
        @endif
        @if (!empty($params['max_date']))
            data-max_date="{{ $params['max_date'] }}"
        @endif
        @if (!isset($params['readonly']) || $params['readonly'] !== false)
            readonly="readonly"
        @endif
        @if (!empty($params['attributes']))
            @foreach ($params['attributes'] as $key => $value)
                {{ ' ' . $key .'="'. $value .'"' }}
            @endforeach
        @endif
    >
    <input type="hidden" name="{{ $from }}" id="{{ $from }}" value="">
    <input type="hidden" name="{{ $to }}" id="{{ $to }}" value="">
    <span class="field-icon"><i class="far fa-calendar-alt"></i></span>
</label>

@push('components-styles')
    <link href="{{ $siteUrl }}/css/daterangepicker.css" rel="stylesheet" type="text/css" />
@endpush

@push('components-scripts')
    <script src="{{ $siteUrl }}/js/moment.min.js"></script>
    <script src="{{ $siteUrl }}/js/daterangepicker-jv.js"></script>
    @if (!empty($params['language']) && $params['language'] === 'es')
        <script src="{{ $siteUrl }}/js/bootstrap-datetimepicker.es.js"></script>
    @endif
    <script>
        // doc: https://www.daterangepicker.com/#usage

        $(function(){
            this.id = "{{ !empty($params['id']) ? $params['id'] : $from .'_'. $to }}";

            window[this.id + 'dateRangePicker'] = $('#' + this.id);
            window[this.id + 'startOfCurrentYear'] = moment().startOf('year');
            window[this.id + 'startOfCurrentMonth'] = moment().startOf('month');
            window[this.id + 'today'] = moment();

            window[this.id + 'dateRangePicker'].daterangepicker({
                iconPrevClass: 'fas fa-chevron-left',
                iconNextClass: 'fas fa-chevron-right',
                minDate: window[this.id + 'startOfCurrentYear'],
                maxDate: window[this.id + 'today'],
                startDate: window[this.id + 'startOfCurrentMonth'],
                endDate: window[this.id + 'today']
            });

            window[this.id + 'minDate'] = window[this.id + 'dateRangePicker'].data('min_date');
            if (window[this.id + 'minDate'] !== "undefined") {
                window[this.id + 'minDate'] = moment(window[this.id + 'minDate'], "YYYY-MM-DD");
                window[this.id + 'dateRangePicker'].data('daterangepicker').minDate = window[this.id + 'minDate'];
            }

            window[this.id + 'maxDate'] = window[this.id + 'dateRangePicker'].data('max_date');
            if (window[this.id + 'maxDate'] !== "undefined") {
                window[this.id + 'maxDate'] = moment(window[this.id + 'maxDate'], "YYYY-MM-DD");
                window[this.id + 'dateRangePicker'].data('daterangepicker').maxDate = window[this.id + 'maxDate'];
            }

            window[this.id + 'dateRangePicker'].on('apply.daterangepicker', function(ev, picker){
                let fromId = picker.element.data('from_id');
                let toId = picker.element.data('to_id');

                $('#'+fromId).val(picker.startDate.format('YYYY-MM-DD'));
                $('#'+toId).val(picker.endDate.format('YYYY-MM-DD'));
            });
        });
    </script>
@endpush
