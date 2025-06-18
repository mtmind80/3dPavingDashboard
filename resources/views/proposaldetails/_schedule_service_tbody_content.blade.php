@foreach($proposalDetail->schedule as $schedule)
    <tr id="schedule_id_{{ $schedule->id }}">
        <td class="tc">{{ $schedule->start_date->format('m/d/Y - h:i A') }}</td>
        <td class="tc">{{ $schedule->end_date->format('m/d/Y - h:i A') }}</td>
        <td class="tc">{{ Str::limit($schedule->title, 100) }}</td>
        <td class="tc">{{ $schedule->creator->full_name ?? null }}</td>
        <td class="tc">{{ $schedule->updater->full_name ?? null }}</td>
        <td class="tc">
            <a
                href="javascript:"
                class="edit-schedule info"
                data-toggle="tooltip"
                title="edit schedule"
                data-schedule_id="{{ $schedule->id }}"
                data-from_date="{{ $schedule->start_date?->format('m/d/Y') }}"
                data-from_time="{{ $schedule->start_date?->format('h:i A') }}"
                data-to_date="{{ $schedule->end_date?->format('m/d/Y') }}"
                data-to_time="{{ $schedule->end_date?->format('h:i A') }}"
                data-title="{{ $schedule->title }}"
                data-note="{{ $schedule->note }}"
            >
                <i class="fas fa-edit fs18 mlr10"></i>
            </a>
            <a
                href="javascript:"
                class="remove-schedule danger"
                data-toggle="tooltip"
                title="delete schedule"
                data-schedule_id="{{ $schedule->id }}"
            >
                <i class="fas fa-trash-alt fs18 mlr10"></i>
            </a>
        </td>
    </tr>
@endforeach