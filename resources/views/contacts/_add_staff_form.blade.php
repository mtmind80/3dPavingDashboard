<input type="hidden" name="returnTo" value="{{ $returnTo ?? null }}">
<input type="hidden" name="tab" value="{{ $tabSelected ?? null }}">
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="first_name" class="check-contact"
                     :params="['label' => 'First Name', 'iconClass' => 'fas fa-user', 'required' => true]"></x-form-text>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="last_name" class="check-contact"
                     :params="['label' => 'Last Name', 'iconClass' => 'fas fa-user', 'required' => true]"></x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
        <x-form-text name="email" class="check-contact"
                     :params="['label' => 'Email', 'iconClass' => 'fas fa-envelope', 'required' => false]"></x-form-text>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
        <x-form-text name="phone"
                     :params="['label' => 'Phone', 'iconClass' => 'fas fa-phone', 'required' => false]"></x-form-text>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 admin-form-item-widget">
        <x-form-text name="title"
                     :params="['label' => 'Title', 'iconClass' => 'fas fa-circle', 'required' => false]"></x-form-text>
    </div>
</div>

@section('page-js')
    <script>
        $(document).ready(function () {

            var firstName = $('#first_name');
            var lastName = $('#last_name');
            var email = $('#email');
            var phone = $('#phone');

            $('#addStaffForm').validate({
                rules: {
                    first_name: {
                        required: true,
                        personName: true
                    },
                    last_name: {
                        required: false,
                        personName: true
                    },
                    email: {
                        required: false,
                        email: true
                    },
                    phone: {
                        required: false,
                        phone: true
                    },
                    title: {
                        required: false,
                        plainText: true
                    },
                },
                messages: {
                    first_name: {
                        required: "@lang('translation.field_required')",
                        personName: "@lang('translation.invalid_entry')"
                    },
                    last_name: {
                        required: "@lang('translation.field_required')",
                        personName: 'Invalid last name.'
                    },
                    email: {
                        required: "@lang('translation.field_required')",
                        email: 'Invalid email.'
                    },
                    phone: {
                        required: "@lang('translation.field_required')",
                        phone: 'Invalid phone.'
                    },
                    title: {
                        required: "@lang('translation.field_required')",
                        plainText: 'Invalid title.'
                    },
                },
                submitHandler: function (form) {
                    let errors = false;

                    if (!errors) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@stop

