@if (!empty($additionalCosts) && $additionalCosts->count() > 0)
    @foreach ($additionalCosts as $additionalCost)
        <tr id="additional_cost_{{ $additionalCost->id }}">
            <td class="tl">{{ $additionalCost->description }}</td>
            <td class="tc">{{ $additionalCost->html_cost }}</td>
            <td class="centered">
                <button
                    class="btn p0 btn-danger tc delete-additional-cost-button"
                    type="button"
                    data-toggle="tooltip"
                    title="Delete item"
                    data-additional_cost_id="{{ $additionalCost->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
