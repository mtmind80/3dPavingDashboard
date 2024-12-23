<table class="list-table table sortable-table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th class="tc fwb">@lang('translation.proposalservices')</th>
            <!--  <th class="tc fwb">@lang('translation.status')</th>
            <th class="tc fwb">@lang('translation.location')</th>
           <th class="tc fwb">@lang('translation.fieldmanager')</th>  -->
            <th class="tc fwb">@lang('translation.cost')</th>
            <th class="tc fwb">@lang('translation.order')</th>
            <th class="actions tc">@lang('translation.actions')</th>
        </tr>
    </thead>
    <tbody id="sortable_body">
        @foreach ($services as $service)
            <tr data-id="{{ $service->id }}">
                <td class="tc"><a href="{{ route('edit_service', ['proposal_id' => $service->proposal_id, 'id' => $service->id]) }}">{{ $service->service_name }}</a></td>
                <!--<td class="tc">{{ $service->status->status ?? 'No Status' }}</td>
                <td class="tc">{!! $service->location->full_location_two_lines ?? 'No Location Specified' !!}</td>
                <td class="tc">{{ $service->fieldmanager->full_name ?? 'No Manager Assigned' }}</td> -->
                <td class="tc">{{ \App\Helpers\Currency::format($service->cost ?? '0.0') }}</td>
                <td class="tc">{{ $service->html_dsort }}</td>
                <td class="centered actions">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-angle-down"></i></a>
                            <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                <li>
                                    <a href="javascript:" class="action" data-action="route" data-route="{{ route('edit_service', ['proposal_id' => $service->proposal_id, 'id' => $service->id]) }}">
                                        <span class="far fa-edit mr4"></span>@lang('translation.edit')
                                    </a>
                                </li>
    <!--                            <li>
                                    <a href="javascript:" class="action" data-action="route" data-route="{{ route('refresh_material', ['id' => $service->proposal_id]) }}">
                                        <span class="far fa-eye mr4"></span>@lang('translation.RefreshMaterials')
                                    </a>
                                </li>
   -->
                                <li class="menu-separator"></li>
                                <li>
                                    <a href="javascript:" class="action" data-action="delete" data-id="{{ $service->id }}">
                                        <span class="far fa-trash-alt mr4"></span>@lang('translation.delete')
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="pt10 no-border">
            <td class="tr" colspan="3">Grand Total:</td>
            <td class="tc">{{ $currency_total_details_costs }}</td>
            <td class="tr" colspan="2"></td>
        </tr>
    </tfoot>
</table>

<x-delete-form
    :url="route('service_delete')"
    :params="[
        'hidden-fields' => [
            'tab' => 'servicestab'
        ]
    ]"
></x-delete-form>


