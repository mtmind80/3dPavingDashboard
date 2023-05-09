
<input type="hidden" name="id" id="id" value="{{ $proposal->id ?? null }}">

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text id="name" name="name" class="check-lead"
                     :params="['label' => 'Proposal Name', 'iconClass' => 'fas fa-circle', 'required' => true]">{{ $proposal['name'] ?? null }}</x-form-text>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">

        {{ $name }}" value="{{ $value }}" id="{{ $id }}"{{ $checked

        <x-form-check-box id="mot_required" value=1 checked = '' name="mot_required" class="check-lead"
                     :params="['slot' => 'MOT Required', 'iconClass' => 'fas fa-car', 'required' => false]">{{ $proposal['mot_required'] ?? null }}</x-form-check-box>
    </div>

</div>
<div class="row buttons">
    <div class="col-sm-12 tr">
        <x-button id="cancel_button" class="btn-light"><i
                    class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption }}</x-button>
        <x-button id="submit_button" class="btn-dark" type="submit"><i class="fas fa-save"></i>{{$submit_caption}}
        </x-button>
    </div>
</div>

@section('page-js')
    <script>
        $(document).ready(function () {
            $('#proposal_form').validate({
                rules: {
                    name: {
                        required: true,
                        personName: true
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
                    window.location = "{{ $returnTo }}";
                } else {
                    window.location = "{{ route('dashboard') }}";
                }
            });
        });
    </script>
@stop

