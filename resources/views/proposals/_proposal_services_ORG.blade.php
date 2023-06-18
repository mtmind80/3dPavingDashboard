@if (!empty($services) && $services->count() > 0)
    <table style="width:100%" class="table table-centered table-bordered">
        <thead>
            <tr style="background:#E5E8E8;color:#000;">
                <td><b>@lang('translation.proposalservices')</b></td>
                <td><b>@lang('translation.status')</b></td>
                <td><b>@lang('translation.location')</b></td>
                <td><b>@lang('translation.fieldmanager')</b></td>
                <td><b>@lang('translation.cost')</b></td>
                <td><b>@lang('translation.action')</b></td>
            </tr>
        </thead>
        <tbody>
        @php
            $totalcost = 0;
        @endphp
        @foreach($services as $service)
            @php
                $totalcost += $service->cost;
            @endphp
            <tr>
                <td>
                    <a href="{{route('edit_service', ['proposal_id'=>$proposal['id'],'id'=>$service->id])}}">{{$service->service_name}}</a>

                    </br>
                </td>
                <td>
                    @if($service->status_id)
                        {{ App\Models\ProposalDetailStatus::find($service->status_id)->status }}
                    @else
                        No Status
                @endif
                <td>
                    @if($service->location_id)
                        {!! App\Models\Location::find($service->location_id)->FullLocationTwoLines !!}
                    @else
                        No Location Specified
                    @endif
                </td>
                <td>
                    @if($service->fieldmanager_id)
                        {{ App\Models\User::find($service->fieldmanager_id)->FullName }}
                    @else
                        No Manager Assigned
                    @endif
                </td>
                <td>
                    {{ \App\Helpers\Currency::format($service->cost ?? '0.0') }}</br>
                </td>
                <td class="centered actions">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"
                               href="#"><i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu animated animated-short flipInX"
                                role="menu">
                                @if($proposal['IsEditable'])
                                    <li>
                                        <a href="{{route('edit_service', ['proposal_id'=>$proposal['id'], 'id'=>$service->id])}}"
                                           class="list-group-item-action">
                                            <span class="fas fa-edit"></span>
                                            &nbsp; @lang('translation.edit')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('refresh_material', ['id'=>$proposal['id']])}}"
                                           class="list-group-item-action">
                                            <span class="far fa-eye"></span>
                                            &nbsp; @lang('translation.RefreshMaterials')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('remove_detail', ['service_id'=>$service->id])}}"
                                           class="list-group-item-action">
                                            <span class="far fa-trash-alt"></span>
                                            &nbsp; @lang('translation.delete')
                                        </a>
                                    </li>
                                @else
                                    @if($service->status_id == 1)
                                        <li>
                                            <a href="{{route('schedule_service', ['service_id'=>$service->id])}}"
                                               class="list-group-item-action">
                                                <span class="far fa-calendar-check"></span>
                                                &nbsp; @lang('translation.schedule')
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{route('schedule_service', ['service_id'=>$service->id])}}"
                                               class="list-group-item-action">
                                                <span class="far fa-calendar-check"></span>
                                                &nbsp; @lang('translation.changeschedule')
                                            </a>
                                        </li>
                                    @endif


                                @endif

                                <li>
                                    <a href="#" id="addmediabutton2"
                                       class="list-group-item-action"
                                       data-action="add-media"
                                       data-route="{{ route('proposal_media_store', ['proposal' => $proposal['id']]) }}"
                                       data-proposal_name="{{ $proposal['name'] }}"
                                       data-service_id = "{{$service->id}}"
                                       data-service_name="{{$service->service_name}}"
                                    >
                                        <span class="far fa-address-card"></span>
                                        &nbsp; @lang('translation.upload')
                                    </a>
                                </li>
                                <!--
                                                                        <li>
                                                                            <a href="{{route('media', ['proposal_id'=>$proposal['id'], 'proposal_detail_id'=>$service->id])}}"
                                                                               class="list-group-item-action">
                                                                                <span class="far fa-eye"></span>
                                                                                &nbsp; @lang('translation.upload')
                                </a>
                            </li>
-->
                            </ul>
                        </li>
                    </ul>
                </td>

            </tr>

        @endforeach
        <tr>
            <td class="tr" colspan="4">Grand Total&nbsp;</td>
            <td class="tc">{{ \App\Helpers\Currency::format($totalcost ?? '0.0') }}</br>
            </td>
            <td>&nbsp;</td>
        </tr>
        </tbody>
    </table>
@endif
