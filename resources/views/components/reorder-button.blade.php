<form method="POST" action="{{ $url }}" accept-charset="UTF-8" id="reorderForm" class="inline-form">
    @csrf
    <input id="reorder_str_cid" name="reorder_str_cid" type="hidden">
    @if (!empty($params['hidden-fields']))
        @foreach ($params['hidden-fields'] as $hiddenFieldName => $hiddenFieldValue)
            <input id="{{ $hiddenFieldName }}" name="{{ $hiddenFieldName }}" type="hidden" value="{{ $hiddenFieldValue }}" >
        @endforeach
    @endif
    <button id="button_update_order" class="btn btn-info mr10 hidden">
        <i class="fas fa-sort-numeric-down mr10"></i>{{ $params['button-label'] ?? 'Update Order' }}
    </button>
</form>
