@extends('layouts.master')

@section('title')
    Proposals
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.Proposals')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            @lang('translation.Proposals')
        @endslot
    @endcomponent
    <div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8 col-sm-6 mb20">
                            <a href="{{ route('new_proposal') }}" title="Select Contact to Create a New Proposal"
                               class="{{$site_button_class}}"><i
                                    class="fas fa-plus"></i>@lang('translation.newproposal')</a>
                        </div>

                        <div class="col-md-4 col-sm-6 float-right mb20">
                        </div>

                    </div>

                </div>
                <div class="card-body">


                    <p>&nbsp;</p>
                    <h4>Showing {{$proposalcount}} active proposals.</h4>
                    <table class="table table-bordered">
                        <thead>
                        <th class="tc">
                            @lang('translation.proposal')
                            @lang('translation.Name')
                        </th>
                        <th class="tc">@lang('translation.client')
                        </th>
                        <th class="tc">
                            @lang('translation.address')
                        </th>
                        <th class="tc">
                            @lang('translation.county')
                        </th>
                        <th class="tc">
                            @lang('translation.date')
                        </th>
                        </thead>
                        <tbody>

                        @if($proposalcount)
                            @foreach ($proposals as $proposal)
                                <tr>
                                    <td class="tc"><a title='Click to Edit'
                                                      href="{{route('show_proposal',['id'=>$proposal['id']])}}">
                                            <span class="ri-ball-pen-fill"></span>
                                            {{$proposal['name']}}</a></td>
                                    <td class="tc">
                                        {{ App\Models\Contact::find($proposal['contact_id'])->FullName }}
                                    </td>
                                    <td class="tc">
                                        @if($proposal['location_id'])
                                            {{ App\Models\Location::find($proposal['location_id'])->RealShortLocation }}
                                        @endif
                                    </td>
                                    <td class="tc">
                                        @if($proposal['location_id'])
                                            {{ App\Models\Location::find($proposal['location_id'])->county }}
                                        @endif
                                    </td>
                                    <td class="tc">
                                        Created:{{ \Carbon\Carbon::parse($proposal['proposal_date'])->format('j F, Y')}}
                                        <br/>
                                        @php
                                            $today = date('Y-m-d'); // today date
                                            $diff = strtotime($today) - strtotime($proposal['proposal_date']);
                                            $days = (int)$diff/(60*60*24);
                                        if($days > 60)
                                            {
                                                echo "<div class='alert-danger'>Days Old:" . intval($days) . "</div";
                                            }
                                        else
                                            {
                                                echo "Days Old:" . intval($days);

                                            }
                                        @endphp
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="tc" colspan="3">No Active Proposals Found</td>
                            </tr>

                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script-bottom')
    <script type="text/javascript">

        $(document).ready(function () {


            $("#submitbutton").click(function (event) {
                form = $("#searchform");
                checkit(form);

            });

            function checkit(form) {

                if ($("#proposal_id").val() != '') {
                    form.submit();
                    return;
                }
                if ($("#proposal_name").val() != '') {
                    form.submit();
                    return;

                }
                if ($("#creator_id").val() > 0) {
                    form.submit();
                    return;

                }

                if ($("#customer_id").val() > 0) {
                    form.submit();
                    return;

                }
                if ($("#contact_id").val() > 0) {
                    form.submit();
                    return;

                }
                if ($("#sales_manager_id").val() > 0) {
                    form.submit();
                    return;

                }
                if ($("#salesperson_id").val() > 0) {
                    form.submit();
                    return;

                }
                if ($("#start_date").val() != '' && $("#end_date").val() != '') {
                    date1 = $("#start_date").val();
                    date2 = $("#end_date").val();
                    date1 = new Date(date1);
                    date2 = new Date(date2);
                    if (date1 > date2) {
                        alert("Start Date must come before the End Date. Please check your dates.")
                        return false;
                    }
                    form.submit();
                    return;

                }

                Swal.fire({
                    title: 'Search Form',
                    text: 'You must select some criteria for your search.',
                    icon: 'error',
                    showConfirmButton: true,

                })

//                alert("You must fill out or select some criteria for your query. You cannot leave all the fields blank");

            }


        });

    </script>
@endsection

@section('script')

    <!-- jquery.vectormap map -->
    <script src="{{ URL::asset('/assets/libs/jquery-vectormap/jquery-vectormap.min.js')}}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js')}}"></script>

@endsection

