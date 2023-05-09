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
            <a href="{{route('show_proposal', ['id'=>$proposal['id']])}}">@lang('translation.proposal')</a>
        @endslot
        @slot('li_2')
            @lang('translation.view') @lang('translation.services')
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">

            <div class="row">
                <table width="100%" class="table-centered table-primary font-size-20">
                    <tr>
                        <td class="p8"><b>@lang('translation.proposalname')</b></td>
                        <td colspan='3' class="p8">{{$proposal['name']}}
                        </td>
                    </tr>
                    <tr>
                        <td class="p8">@lang('translation.salesmanager')</td>
                        <td class="p8">
                            @if($proposal['salesmanager_id'])
                                {{ App\Models\User::find($proposal['salesmanager_id'])->FullName }}
                            @endif

                        </td>
                        <td class="p8">@lang('translation.salesperson')</td>
                        <td class="p8">
                            @if($proposal['salesperson_id'])
                                {{ App\Models\User::find($proposal['salesperson_id'])->FullName }}
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td class="p8">@lang('translation.client')</td>
                        <td class="p8">
                            @if($proposal['contact_id'])
                                {{ App\Models\Contact::find($proposal['contact_id'])->FullName }}
                            @endif
                        </td>
                        <td class="p8">@lang('translation.proposaldate')</td>
                        <td class="p8">
                            {{ \Carbon\Carbon::parse($proposal['proposal_date'])->format('j F, Y') }}
                        </td>
                    </tr>
                </table>
                
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">

            <div class="row">


            </div>
        </div>
    </div>


@endsection


@section('script-bottom')
    <script type="text/javascript">

        $(document).ready(function () {


            $("#submitbutton").click(function (event) {
                form = $("#editform");
                if (!checkit(form)) {
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
