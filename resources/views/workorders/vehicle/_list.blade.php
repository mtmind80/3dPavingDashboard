@if (!empty($vehicles) && $vehicles->count() > 0)
    @foreach ($vehicles as $vehicle)
        <tr id="vehicle_{{ $vehicle->id }}">
            <td class="tc">{{ $vehicle->report_date->format('m/d/Y') }}</td>
            <td class="tc">{{ $vehicle->vehicle_name }}</td>
            <td class="tc">{{ $vehicle->number_of_vehicles }}</td>
            <td class="tc">{{ $vehicle->note }}</td>
            <td class="centered">
                <button
                    class="btn p0 btn-danger tc delete-vehicle-button"
                    type="button"
                    data-toggle="tooltip"
                    title="Delete item"
                    data-vehicle_id="{{ $vehicle->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
