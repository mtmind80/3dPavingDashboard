@extends('layouts.master')

@section('title')
    3D Paving Proposals
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('title')
            @lang('translation.edit') @lang('translation.proposal')
        @endslot
        @slot('li_1')
            <a href="/dashboard">@lang('translation.Dashboard')</a>
        @endslot
        @slot('li_2')
            <a href="/proposals">@lang('translation.Proposals')</a>
        @endslot
        @slot('li_3')
            @lang('translation.print') @lang('translation.proposal')
        @endslot
    @endcomponent

    <div class="row admin-form">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('print_proposal_with_images') }}"
                          accept-charset="UTF-8" id="proposal_form" class="admin-form">
                        @csrf

                        <input type="hidden" name="proposal_id" id="proposal_id" value={{$proposal_id}}>
                        <input type="hidden" name="print_date" id="print_date" value={{$print_date}}>

                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Proposal Information</h3>
                                {{ $proposal['name']}}
                            </div>
                        </div>



                            @if(count($medias))
                                <div class="row">

                                    <div class="col-sm-12">
                                    <h4>Attach Media to Proposal</h4>
                                    Print Date: {{$print_date}}
                                    </div>
                                    <div class="col-sm-12">
                                        <table style="width:100%" class="table table-centered table-bordered">
                                            <thead>
                                            <tr style="background:#E5E8E8;color:#000;">
                                                <td><b></b>Attach @lang('translation.media')</b></td>
                                                <td><b></b>@lang('translation.media') @lang('translation.type')</b></td>
                                                <td><b>@lang('translation.Name')</b></td>
                                                <td><b>@lang('translation.description')</b></td>
                                                <td><b>@lang('translation.File_Upload') @lang('translation.date')</b></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($medias as $media)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selectmedia[]" value="{{$media->id}}">
                                                    </td>
                                                    <td>
                                                        @if($media->media_type_id)
                                                            {{ App\Models\MediaType::find($media->media_type_id)->type}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$media->original_name}}
                                                    </td>
                                                    <td>
                                                        {{$media->description}}
                                                    </td>
                                                    <td>
                                                        @if($media->created_by)
                                                            {{ App\Models\User::find($media->created_by)->FullName }} </br>
                                                        @endif
                                                        {{ \Carbon\Carbon::parse($media->created_at)->format('m/d/yy') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            @else
                            No Media Found
                            @endif

                        <div class="row buttons">
                            <div class="col-sm-12 tr">
                                <x-button id="cancel_button" class="btn-light"><i
                                            class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption }}</x-button>
                                <x-button id="submit_button" class="btn-dark" type="submit"><i
                                            class="fas fa-save"></i>{{$submit_caption}}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-js')
    <script>
        $(document).ready(function () {
            $('#proposal_form').validate({
                rules: {
                    name: {
                        required: true,
                        plainText: true
                    },
                },
                messages: {
                    name: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    },
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });

            $('#cancel_button').click(function () {
                if ("{{ $returnTo }}" !== "") {
                    window.location.href = "{{ $returnTo }}";
                } else {
                    window.location.href = "{{ route('show_proposal',['id'=>$proposal['id']]) }}";
                }
            });



            $("#printproposal").click(function () {


                if(checkform()) {

                    let timerInterval
                    var print_date = $("#print_date").val();
                    Swal.fire({
                        title: 'Working On It!',
                        html: '</br><h3>Preparing your proposal for download.</h3><br/>',
                        icon: 'info',
                        heightAuto: false,
                        timerProgressBar: true,
                        timer: 1000,
                        width: '80em',
                        showConfirmButton: true,
                    })

                    window.location.href = "/print/proposal/?proposal_id={{$proposal['id']}}&print_date=" + print_date;
                    return;

                }
                alert("You must select a sales person before printing the contract. Edit the proposal and select a sales person.")

            });

        });


    </script>
@stop

