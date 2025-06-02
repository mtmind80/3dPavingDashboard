@if (!empty($timeSheets) && $timeSheets->count() > 0)
    @foreach ($timeSheets as $timeSheet)
        <tr id="timesheet_{{ $timeSheet->id }}">
            <td class="tc">{{ $timeSheet->employee->full_name ?? null }}</td>
            <td class="tc">{{ $timeSheet->html_start }}</td>
            <td class="tc">{{ $timeSheet->html_finish }}</td>
            <td class="tc">{{ round($timeSheet->actual_hours, 2) }}</td>
            <td class="centered">
                <button
                    class="btn p0 btn-danger tc delete-timesheet-button"
                    type="button"
                    data-toggle="tooltip"
                    title="Delete item"
                    data-timesheet_id="{{ $timeSheet->id }}"
                >
                    <i class="far fa-trash-alt dib m0 plr5"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif
