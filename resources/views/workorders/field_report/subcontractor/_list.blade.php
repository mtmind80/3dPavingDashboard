@if (!empty($subcontractors) && $subcontractors->count() > 0)
    @foreach ($subcontractors as $subcontractor)
        <tr id="subcontractor_{{ $subcontractor->id }}">
            <td class="tc">{{ $subcontractor->subcontractor->name ?? null }}</td>
            <td class="tc">{{ $subcontractor->html_cost }}</td>
            <td class="tc">{{ $subcontractor->description }}</td>
            <td class="centered">
                <button
                    class="btn p0 btn-danger tc delete-subcontractor-button"
                    type="button"
                    data-toggle="tooltip"
                    title="Delete item"
                    data-subcontractor_id="{{ $subcontractor->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
