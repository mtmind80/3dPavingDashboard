@if (!empty($equipments) && $equipments->count() > 0)
    @foreach ($equipments as $equipment)
        <tr id="equipment_{{ $equipment->id }}">
            <td class="tc">{{ $equipment->name }}</td>
            <td class="tc">{{ $equipment->hours }}</td>
            <td class="tc">{{ $equipment->rate_type }}</td>
            <td class="tc">{{ $equipment->html_rate }}</td>
            <td class="tc">{{ $equipment->number_of_units }}</td>
            <td class="centered">
                <button
                    class="btn p0 btn-danger tc delete-equipment-button"
                    type="button"
                    data-toggle="tooltip"
                    title="Delete item"
                    data-equipment_id="{{ $equipment->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
