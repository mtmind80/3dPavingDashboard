<input type="hidden" name="returnTo" value="{{ $returnTo ?? null }}">
<input type="hidden" name="tab" value="{{ $tabSelected ?? null }}">
<input type="hidden" name="lead_id" id="lead_id" value="{{ $lead->lead_id ?? null }}">

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-select name="contact_type_id" :items="$typesCB" selected="{{ $lead->contact_type_id ?? null }}"
                       :params="['label' => 'Lead Is A', 'required' => true]"></x-form-select>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text id="community_name" name="community_name" class="check-lead"
                     :params="['label' => 'Company/Community Name', 'iconClass' => 'fas fa-circle', 'required' => false]">{{ $lead->community_name ?? null }}</x-form-text>
    </div>

</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="first_name" class="check-lead"
                     :params="['label' => 'Caller First Name', 'iconClass' => 'fas fa-user', 'required' => true]">{{ $lead->first_name ?? null }}</x-form-text>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="last_name" class="check-lead"
                     :params="['label' => 'Caller Last Name', 'iconClass' => 'fas fa-user', 'required' => false]">{{ $lead->last_name ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="email" class="check-lead"
                     :params="['label' => 'Email', 'iconClass' => 'fas fa-envelope', 'required' => false]">{{ $lead->email ?? null }}</x-form-text>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="phone"
                     :params="['label' => 'Phone', 'iconClass' => 'fas fa-phone', 'required' => true]">{{ $lead->phone ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 admin-form-item-widget">
        <x-form-text name="address1"
                     :params="['label' => 'Address', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $lead->address1 ?? null }}</x-form-text>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 admin-form-item-widget">
        <x-form-text name="address2"
                     :params="['label' => 'Address 2', 'iconClass' => 'fas fa-building', 'required' => false]">{{ $lead->address2 ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="city"
                     :params="['label' => 'City', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $lead->city ?? null }}</x-form-text>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-select name="county" :items="$countiesCB" selected="{{ $lead->county ?? null }}"
                       :params="['label' => 'County', 'required' => true]"></x-form-select>
    </div>
    <div class="col-lg-3 col-md-8 col-sm-8 admin-form-item-widget">
        <x-form-text name="state"
                     :params="['label' => 'State', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $lead->state ?? 'FL' }}</x-form-text>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 admin-form-item-widget">
        <x-form-text name="postal_code"
                     :params="['label' => 'Zipcode', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $lead->postal_code ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-select name="lead_source" :items="$sources" selected="{{ $lead->lead_source ?? null }}"
                       :params="['label' => 'Lead Source', 'required' => true]"></x-form-select>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 admin-form-item-widget">
        <x-form-text name="how_related"
                     :params="['label' => 'How is Caller Related', 'iconClass' => 'fas fa-building', 'required' => false]">{{ $lead->how_related ?? null }}</x-form-text>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 admin-form-item-widget">
        <x-form-check-box class="mt5" name="onsite" id="onsite" value="1" checked="{{ !empty($lead->onsite) }}">Caller
            can meet Onsite?
        </x-form-check-box>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-check-box class="mt5" name="worked_before" id="worked_before" value="1"
                          checked="{{ !empty($lead->worked_before) }}">Previous Client / Did Work Before?
        </x-form-check-box>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-select name="previous_assigned_to" :items="$managersCB"
                       selected="{{ $lead->previous_assigned_to ?? null }}"
                       :params="['label' => 'Previous Assigned To', 'required' => false]"></x-form-select>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 admin-form-item-widget">
        <x-form-textarea style="height:135px" name="worked_before_description"
                         :params="['label' =>  'Worked Before Description', 'iconClass' => 'fas fa-sticky-note', 'required' => false]">{{ $lead->worked_before_description ?? null }}</x-form-textarea>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
        <x-form-textarea style="height:135px" name="type_of_work_needed"
                         :params="['label' =>  'Type Of Work Needed', 'iconClass' => 'fas fa-sticky-note', 'required' => true]">{{ $lead->type_of_work_needed ?? null }}</x-form-textarea>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="best_days"
                     :params="['label' => 'Best Day/Time to Call', 'iconClass' => 'fas fa-bookmark', 'required' => true]">{{ $lead->best_days ?? null }}</x-form-text>
    </div>
    <div class="col-lg-3 not-lg-hidden admin-form-item-widget"></div>
</div>
<div class="row buttons">
    <div class="col-sm-12 tr">
        <x-button id="cancel_button" class="btn-light"><i
                    class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption }}</x-button>
        <x-button id="submit_button" class="btn-dark" type="submit"><i class="fas fa-save"></i>{{ $submit_caption }}
        </x-button>
    </div>
</div>

@section('page-js')
    <script>
        $(document).ready(function () {
            $('#admin_form').validate({
                rules: {
                    first_name: {
                        required: true,
                        personName: true
                    },
                    last_name: {
                        required: true,
                        personName: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        phone: true
                    },
                    address1: {
                        required: true,
                        plainText: true
                    },
                    address2: {
                        required: false,
                        plainText: true
                    },
                    city: {
                        required: true,
                        plainText: true
                    },
                    county: {
                        required: true,
                        plainText: true
                    },
                    state: {
                        required: true,
                        plainText: true
                    },
                    postal_code: {
                        required: true,
                        zipCode: true
                    },
                    previous_assigned_to: {
                        required: false,
                        zeroOrPositive: true
                    },
                    lead_source: {
                        required: true,
                        plainText: true
                    },
                    how_related: {
                        required: false,
                        plainText: true
                    },
                    worked_before: {
                        required: false,
                        boolean: true
                    },
                    worked_before_description: {
                        required: $('#worked_before').prop('checked'),
                        plainText: true
                    },
                    onsite: {
                        required: false,
                        boolean: true
                    },
                    best_days: {
                        required: true,
                        plainText: true
                    }
                },
                messages: {
                    first_name: {
                        required: "@lang('translation.field_required')",
                        personName: "@lang('translation.invalid_first_name')"
                    },
                    last_name: {
                        required: "@lang('translation.field_required')",
                        personName: "@lang('translation.invalid_last_name')"
                    },
                    email: {
                        required: "@lang('translation.field_required')",
                        email: "@lang('translation.invalid_email')"
                    },
                    alt_email: {
                        email: "@lang('translation.invalid_email')"
                    },
                    phone: {
                        required: "@lang('translation.field_required')",
                        phone: "@lang('translation.invalid_phone')"
                    },
                    address1: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_address')"
                    },
                    address2: {
                        required: 'This field is required when Billing Address 2 is defined.',
                        plainText: "@lang('translation.invalid_address')"
                    },
                    city: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_city')"
                    },
                    state: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_state')"
                    },
                    postal_code: {
                        required: "@lang('translation.field_required')",
                        zipCode: "@lang('translation.invalid_zip_code')"
                    },
                    lead_source: {
                        plainText: "@lang('translation.select_item')"
                    },
                    how_related: {
                        plainText: "@lang('translation.invalid_entry')"
                    },
                    worked_before: {
                        boolean: "@lang('translation.invalid_entry')"
                    },
                    worked_before_description: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    },
                    onsite: {
                        boolean: true
                    },
                    best_days: {
                        required: "@lang('translation.field_required')",
                        plainText: "@lang('translation.invalid_entry')"
                    }
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
                    window.location = "{{ route('lead_list') }}";
                }
            });
        });
    </script>
@stop

