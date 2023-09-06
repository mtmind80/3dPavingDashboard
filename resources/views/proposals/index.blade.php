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
                            <a href="{{ route('new_proposal') }}" title="Select Contact to Create a New Proposal" class="{{$site_button_class}}"><i
                                        class="fas fa-plus"></i>@lang('translation.newproposal')</a>
                        </div>

                        <div class="col-md-4 col-sm-6 float-right mb20">
                            <a href="{{ route('inactive_proposals') }}" title="View InActive Proposals" class="{{$site_button_class}}"><i
                                        class="fas fa-door-closed"></i>@lang('translation.inactiveProposal')</a>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <h4 class="card-title">@lang('translation.Search') @lang('translation.active') @lang('translation.Proposals')</h4>
                    <p class="card-title-desc">@lang('translation.PDESC').</p>
                    <form class="custom-validation" action="{{ route('search_proposals') }}" novalidate="" method="POST"
                          id="searchform">
                        @csrf


                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('translation.ProposalID')</label>
                                <div>
                                    <input name="proposal_id" id="proposal_id" data-parsley-type="number" type="text"
                                           class="form-control" placeholder="Enter only numbers">
                                </div>
                            </div>

                            <div class="form-group" col-lg-6>
                                <label>@lang('translation.ProposalName')</label>
                                <input name="proposal_name" id="proposal_name" size='54' type="text"
                                       class="form-control" placeholder="Enter proposal name">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group  col-lg-6">
                                <label>@lang('translation.CreatedBy')</label>

                                @if (!empty($creatorsCB))
                                    <select id="creator_id" class="custom-select custom-select-sm"
                                            name="creator_id">
                                        <option value="0">ANY</option>
                                        @foreach ($creatorsCB as $id => $fullName)
                                            <option value="{{ $id }}">{{ $fullName }}</option>
                                        @endforeach
                                    </select>
                                @endif

                            </div>
                            <div class="form-group  col-lg-6">
                                <label>@lang('translation.Customer')</label>
                                @if (!empty($customersCB))
                                    <select id="contact_id" class="custom-select custom-select-sm"
                                            name="contact_id">
                                        <option value="0">ANY</option>
                                        @foreach ($customersCB as $id => $fullName)
                                            <option value="{{ $id }}">{{ $fullName }} {{ $id }}</option>
                                        @endforeach
                                    </select>
                                @endif

                            </div>

                        </div>
                        <div class="row">

                            <div class="form-group col-lg-6">
                                <label>@lang('translation.SalesManager')</label>
                                @if (!empty($salesManagersCB))
                                    <select id="sales_manager_id" class="custom-select custom-select-sm"
                                            name="sales_manager_id">
                                        <option value="0">ANY</option>
                                        @foreach ($salesManagersCB as $id => $fullName)
                                            <option value="{{ $id }}">{{ $fullName }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="form-group col-lg-6">
                                @if (!empty($salesPersonsCB))
                                    <label>@lang('translation.SalesPerson')</label>
                                    <select id="salesperson_id" class="custom-select custom-select-sm"
                                            name="salesperson_id">
                                        <option value="0">ANY</option>
                                        @foreach ($salesPersonsCB as $id => $fullName)
                                            <option value="{{ $id }}">{{ $fullName }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-lg-6">
                                <label>@lang('translation.proposal') @lang('translation.CreatedDate') @lang('translation.between')</label>
                                <input class="form-control" type="date" value="" id="start_date" name="start_date">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>And</label>
                                <input class="form-control" type="date" value="" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-lg-4">

                                <input class="checkbox-inline" type="checkbox" value="1" id="showall" name="showall">
                                <label>@lang('translation.showall') </label>
                            </div>
                            <div class="form-group col-lg-8">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-0  col-lg-12">
                                <div>
                                    <button type="button" id='submitbutton'
                                            class="btn btn-primary waves-effect waves-light mr-1">Submit
                                    </button>
                                    <button type="reset" class="btn btn-secondary waves-effect">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
<p>&nbsp;</p>
                    <h4>You have {{$proposalcount}} active proposals.</h4>
                    <table class="table table-bordered">
                        <thead>
                        <th class="tc">
                            @lang('translation.proposal')
                            @lang('translation.Name')
                        </th>
                        <th class="tc">@lang('translation.client')
                        </th>
                        <th class="tc">
                            @lang('translation.status')
                        </th>
                        <th class="tc">
                            @lang('translation.date')
                        </th>
                        </thead>
                        <tbody>

                        @if($proposalcount)
                        @foreach ($proposals as $proposal)
                            <tr>
                                <td class="tc"><a title='Click to Edit' href="{{route('show_proposal',['id'=>$proposal['id']])}}">
                                        <span class="ri-ball-pen-fill"></span>
                                        {{$proposal['name']}}</a></td>
                                <td class="tc">
                                    {{ App\Models\Contact::find($proposal['contact_id'])->FullName }}
                                </td>
                                <td class="tc">
                                    @if($proposal['proposal_statuses_id'])
                                        {{ App\Models\ProposalStatus::find($proposal['proposal_statuses_id'])->status }}
                                    @endif
                                </td>
                                <td class="tc">{{$proposal['proposal_date']}}</td>

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
                alert("You must fill out or select some criteria for your query. You cannot leave all the fields blank");

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

