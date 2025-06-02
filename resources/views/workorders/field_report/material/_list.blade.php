@if (!empty($materials) && $materials->count() > 0)
    @foreach ($materials as $material)
        <tr id="material_{{ $material->id }}">
            <td class="tc">{{ $material->name }}</td>
            <td class="tc">{{ $material->quantity }}</td>
            <td class="tc">{{ \App\Helpers\Currency::format($material->quantity * $material->cost) }}</td>
            <td class="tc">{{ $material->note }}</td>
            <td class="centered">
                <button
                    class="btn p0 btn-danger tc delete-material-button"
                    type="button"
                    data-toggle="tooltip"
                    title="Delete item"
                    data-material_id="{{ $material->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
