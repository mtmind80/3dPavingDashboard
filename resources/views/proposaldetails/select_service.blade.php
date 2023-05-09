@extends('layouts.master')

@section('title')
    3D Paving
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            {{$headername}}
        @endslot
        @slot('li_1')
            <a href="{{route('show_proposal', ['id'=>$proposal_id])}}">@lang('translation.proposal')</a>
        @endslot
        @slot('li_2')
            @lang('translation.selectservice')
        @endslot
    @endcomponent

    <div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">

                    <form
                            action="{{ route('create_detail',['proposal_id'=>$proposal_id]) }}" novalidate=""
                            method="POST"
                            id="editform">
                        @csrf
                        
                        <input type="hidden" name="location_id" value="{{$proposal['location_id']}}">
                        <div class="row">
                            <div class="form-group col-lg-12">

                                <table class="table table-centered border-danger w_100">
                                    <tr>
                                        <td class="p4"><b>@lang('translation.proposalname')</b></td>
                                        <td colspan='3' class="p4">{{$proposal['name']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p4">@lang('translation.salesmanager')</td>
                                        <td class="p4">
                                            @if($proposal['salesmanager_id'])
                                                {{ App\Models\User::find($proposal['salesmanager_id'])->FullName }}
                                            @endif

                                        </td>
                                        <td class="p4">@lang('translation.salesperson')</td>
                                        <td class="p4">
                                            @if($proposal['salesperson_id'])
                                                {{ App\Models\User::find($proposal['salesperson_id'])->FullName }}
                                            @endif

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p4">@lang('translation.client')</td>
                                        <td class="p4">
                                            @if($proposal['contact_id'])
                                                {{ App\Models\Contact::find($proposal['contact_id'])->FullName }}
                                            @endif
                                        </td>
                                        <td class="p4">@lang('translation.proposaldate')</td>
                                        <td class="p4">
                                            {{ \Carbon\Carbon::parse($proposal['proposal_date'])->format('j F, Y') }}
                                        </td>
                                    </tr>
                              
                                </table>


                            </div>
                        </div>
                        <table class="table-centered font-size-16" width="90%">
                            <tr>
                                <td class='tc'>
                                    Select
                                </td>
                                <td class='tc'>
                                    Name
                                </td>
                                <td class='tc'>
                                    Select
                                </td>
                                <td class='tc'>
                                    Name
                                </td>
                                @php($c =1)
                                @foreach($servicecats as $cat)
                                    @php($c = $c + 1)
                                    @if($c/2 == intval($c/2))
                            </tr>
                            <tr>
                                @endif
                                <td class='tc'>
                                    <input type="radio" name="servicecat" value="{{$cat['id']}}">
                                </td>
                                <td class='tc'>
                                    {{$cat['name']}}
                                </td>

                                @endforeach
                            </tr>
                        </table>
                </div>

            </div>
            <div class="row">
                <div class="form-group mb-0  col-lg-12">
                    <div>
                        <button type="button" id='submitbutton'
                                class="btn btn-primary waves-effect waves-light mr-1">@lang('translation.submit')
                        </button>
                        <button type="button" id="cancelbutton"
                                class="btn btn-danger waves-effect">@lang('translation.cancel')</button>
                    </div>
                </div>
            </div>
            </form>


        </div>
    </div>
    </div>
    </div>
@endsection


@section('script-bottom')
    <script type="text/javascript">

        $(document).ready(function () {


            $("#cancelbutton").click(function (event) {

                window.location.href='{{ route('show_proposal',['id'=>$proposal_id]) }}';
            });
            
            $("#submitbutton").click(function (event) {
                form = $("#editform");
                if(!checkit(form)){
                    alert("You must select a service");
                    return;
                }
                form.submit();

            });

            function checkit(form) {
                return ($('input[name=servicecat]:checked').length > 0);
            }


        });

    </script>
@endsection
