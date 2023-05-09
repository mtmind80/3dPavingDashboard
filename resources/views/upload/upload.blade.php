@extends('layouts.master')

@section('title')
    3D Paving Uploads
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.upload')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.dashboard')</a>
        @endslot

        @slot('li_2')
            <a href="{{route('show_proposal',['id'=>$proposal_id])}}"> @lang('translation.proposal')</a>
        @endslot

        @slot('li_3')
            @lang('translation.upload')
        @endslot
    @endcomponent

    <div id="uploadtypes">
        <form id="uploadform" class="custom-validation" novalidate="" action="{{route('doupload')}}" method="post"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="proposal_id" value="{{$proposal_id}}">
            <input type="hidden" name="proposal_detail_id" value="{{$proposal_detail_id}}">

            <h3>{{$proposal['name']}}</h3>
            <table class="table table-bordered w-50">
                <tr>
                    <td>
                        Media Type
                    </td>
                </tr>
                <tr>
                    <td>
                        <select class="form-control" id="media_type_id" name="media_type_id">
                            @foreach ($mediatypes as $mediatype)
                                <option value="{{$mediatype['id']}}">{{$mediatype['type']}}</option>

                            @endforeach
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="ri-asterisk alert-danger"></span>Description
                    </td>
                </tr>
                <tr>
                    <td>
                    <textarea data-parsley-required="true"
                              class="form-control" placeholder="Enter a brief description" rows='3' , cols='60'
                              id="description" name="description"></textarea>
                    </td>
                </tr>
                <tr>

                    <td>

                        <input class="form-control" type="file" name="file" id="file">
                        Acceptable file formats : {{$doctypes}}
                    </td>
                </tr>

                <tr>
                    <td class="tc">
                        <input class="btn btn-success" type="button" id="submitbutton" value="Upload">
                        <input class="btn btn-info" type="button" id="cancelbutton" value="Cancel">
                        <input class="btn btn-outline-info" type="reset" id="reset" value="Reset">
                    </td>
                </tr>
            </table>
        </form>
    </div>

@endsection


@section('script-bottom')
    <script type="text/javascript">

        $(document).ready(function () {


            $("#cancelbutton").click(function (event) {
                window.location.href = '{{route('show_proposal', ['id'=>$proposal_id])}}';
                return true;

            });

            $("#submitbutton").click(function (event) {
                form = $("#uploadform");
                checkit(form);

            });

            function checkit(form) {

                if ($("#file").val() == '') {
                    alert("You must fill select a file to upload");
                    return;
                }

                if ($("#description").val() == '') {
                    alert("You must fill in a description");
                    return;
                }

                form.submit();
                return;


            }


        });

    </script>
@endsection
