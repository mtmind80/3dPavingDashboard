<input type="hidden" name="returnTo" value="{{ $returnTo ?? null }}">
<input type="hidden" name="tab" value="{{ $tabSelected ?? null }}">

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <span class="form-field-label"> Contact Type:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="" data-original-title="field requirede"></i></span>
        <label class="field select prepend-icon">
            <select name="contact_type_id" id="contact_type_id" value="" class="form-control grayed ">
                <option value="0">Select type</option>
                @foreach ($types as $type)
                    <option
                        id="value_id_{{ $type->id }}"
                        value="{{ $type->id }}"
                        data-entity_type="{{ $type->entity_type }}"
                        @if (!empty($contact->contact_type_id) && (int) $contact->contact_type_id === (int) $type->id)
                            selected="selected"
                        @endif
                    >
                        {{ $type->type }}
                    </option>
                @endforeach
            </select>
            <i class="arrow double"></i>
            <span class="field-icon"><i class="fas fa-indent"></i></span>
        </label>
        {{--
        <x-form-select name="contact_type_id" id="contact_type_id" :items="$typesCB" selected="{{ $contact->contact_type_id ?? old('contact_type_id') ?? null }}" :params="['label' => 'Contact Type', 'required' => true]"></x-form-select>
        --}}
    </div>
</div>
<div class="row">
    <div
        id="person_first_name_fields_container"
        class="
            col-lg-3 col-md-6 col-sm-6 admin-form-item-widget
            @if (empty($contact->contact_type_id) || $contact->contactType->entity_type !== 'person')
                hidden
            @endif
        "
    >
        <x-form-text name="{{ !empty($contact->contact_type_id) && $contact->contactType->entity_type === 'person' ? 'first_name' : '' }}" class="check-contact" :params="['label' => 'First Name', 'iconClass' => 'fas fa-user', 'required' => true]">{{ $contact->first_name ?? old('first_name') ?? null }}</x-form-text>
    </div>

    <div
        id="person_last_name_fields_container"
        class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget
            @if (empty($contact->contact_type_id) || $contact->contactType->entity_type !== 'person')
                hidden
            @endif
        "
    >
        <x-form-text name="last_name" class="check-contact" :params="['label' => 'Last Name', 'iconClass' => 'fas fa-user', 'required' => false]">{{ $contact->last_name ?? old('last_name') ?? null }}</x-form-text>
    </div>

    <div
        id="company_name_field_container"
        class="col-lg-6 col-md-6 col-sm-6 admin-form-item-widget
            @if (empty($contact->contact_type_id) || $contact->contactType->entity_type !== 'company')
                hidden
            @endif
        "
    >
        <x-form-text name="{{ !empty($contact->contact_type_id) && $contact->contactType->entity_type === 'company' ? 'first_name' : '' }}" class="check-contact" :params="['label' => 'Company Name', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->first_name ?? old('first_name') ?? null }}</x-form-text>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="email" class="check-contact" :params="['label' => 'Email', 'iconClass' => 'fas fa-envelope', 'required' => true]">{{ $contact->email ??  old('email') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="alt_email" :params="['label' => 'Alt Email', 'iconClass' => 'fas fa-envelope', 'required' => false]">{{ $contact->alt_email ?? old('alt_email') ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="phone" :params="['label' => 'Phone', 'iconClass' => 'fas fa-phone', 'required' => true]">{{ $contact->phone ?? old('phone') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="alt_phone" :params="['label' => 'Alt Phone', 'iconClass' => 'fas fa-phone', 'required' => false]">{{ $contact->alt_phone ?? old('alt_phone') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-4 col-md-8 col-sm-8 admin-form-item-widget">
        <x-form-text name="address1" :params="['label' => 'Address', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->address1 ?? old('address1') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 admin-form-item-widget">
        <x-form-text name="address2" :params="['label' => 'Address 2', 'iconClass' => 'fas fa-building', 'required' => false]">{{ $contact->address2 ?? old('address2') ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-select id="county" name="county" :items="$countiesCB" selected="{{ $contact->county ?? old('county') ?? null }}"  :params="['label' => 'County', 'required' => true]"></x-form-select>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 admin-form-item-widget">
        <label>City:</label>
        <select name="city" id="city" class="form-control">
            <option value="0">Select county first</option>
        </select>
    </div>
    {{--
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-text name="county" :params="['label' => 'County', 'iconClass' => 'fas fa-building', 'required' => true]"></x-form-text>
    </div>
    --}}
    <div class="col-lg-3 col-md-8 col-sm-8 admin-form-item-widget">
        <x-form-text name="state" :params="['label' => 'State', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->state ?? old('state') ?? 'FL' }}</x-form-text>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 admin-form-item-widget">
        <x-form-text name="postal_code" :params="['label' => 'Zipcode', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->postal_code ?? old('postal_code') ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 admin-form-item-widget">
        <x-form-check-box class="not-xs-mt10" name="same_billing_address" id="same_billing_address" value="1" checked="{{ !empty($contact->same_billing_address)  }}">Billing Address Same as Above</x-form-check-box>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-4 admin-form-item-widget xs-hidden"></div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-8 col-sm-9 admin-form-item-widget">
        <x-form-text name="billing_address1" :params="['label' =>  'Billing Address', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->billing_address1 ?? old('billing_address1') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-3 admin-form-item-widget">
        <x-form-text name="billing_address2" :params="['label' => 'Billing Address 2', 'iconClass' => 'fas fa-building', 'required' => false]">{{ $contact->billing_address2 ?? old('billing_address2') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-3 col-md-7 col-sm-6 admin-form-item-widget">
        <x-form-text name="billing_city" :params="['label' => 'Billing City', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->billing_city ?? old('billing_city') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 admin-form-item-widget">
        <x-form-text name="billing_state" :params="['label' => 'Billing State', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->billing_state ?? old('billing_state') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 admin-form-item-widget">
        <x-form-text name="billing_postal_code" :params="['label' => 'Billing Zipcode', 'iconClass' => 'fas fa-building', 'required' => true]">{{ $contact->billing_postal_code ?? old('billing_postal_code') ?? null }}</x-form-text>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget">
        <x-form-text name="contact" :params="['label' =>  'Contact', 'iconClass' => 'fas fa-user', 'required' => false]">{{ $contact->contact ?? old('contact') ?? null }}</x-form-text>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 admin-form-item-widget xs-hidden"></div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 admin-form-item-widget">
        <x-form-textarea style="height:135px" name="note" :params="['label' =>  'Memo', 'iconClass' => 'fas fa-sticky-note', 'required' => false]">{{ $contact->note ?? old('note') ?? null }}</x-form-textarea>
    </div>
</div>

<div class="row buttons pt20">
    <div class="col-sm-12 tr">
        <x-button id="cancel_button" class="btn-light"><i class="far fa-arrow-alt-circle-left "></i>{{ $cancel_caption }}</x-button>
        <x-button id="submit_button" class="btn-dark" type="submit"><i class="fas fa-save"></i>{{ $submit_caption }}</x-button>
    </div>
</div>

@include('modals.existing_contacts_modal')

@section('css-files')
    <!-- -->
@stop

@section('page-js')
    <script>
        $(document).ready(function(){

            var zipCode = '33234';

            //alert(isZipCode(zipCode));

            var personFirstNameFieldsContainer = $('#person_first_name_fields_container');
            var personFirstNameInputField = personFirstNameFieldsContainer.find('input:text');
            var personLastNameFieldsContainer = $('#person_last_name_fields_container');
            
            var companyNameFieldContainer = $('#company_name_field_container');
            var companyNameInputField = companyNameFieldContainer.find('input:text');

            var contactTypeIdSelect = $('#contact_type_id');

            var countyEl = $('#county');
            var cityEl = $('#city');


            console.log(countyEl);
            console.log(cityEl);

            countyEl.on('change', function(){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        county: $(this).val()
                    },
                    type: "POST",
                    url: "{{ route('ajax_fetch_cities') }}",
                    beforeSend: function (request) {
                        showSpinner();
                    },
                    complete: function () {
                        hideSpinner();
                    },
                    success: function (response) {
                        if (typeof response.success === 'undefined' || !response) {
                            cityEl.html('<option value="0">Select county last</option>');
                            console.log('Critical error has occurred.');
                        } else if (response.success) {
                            let data = response.data;
                            let html = '<option>NA</option>';

                            $.each(data, function(key, value){
                                html += '<option>'+ value +'</option>';
                            })
                            cityEl.html(html);
                        } else {
                            // controller defined response error message
                            cityEl.html('<option value="0">Select county first</option>');
                            console.log(response.message);
                        }
                    },
                    error: function (response) {
                        cityEl.html('<option value="0">Select county first</option>');
                        @if (app()->environment() === 'local')
                        console.log(response.responseJSON.message);
                        @else
                        console.log(response.message);
                        @endif
                    }
                });
            });

            var sameBillingAddress = $('#same_billing_address');
            var address1 = $('#address1');
            var address2 = $('#address2');
            var city = $('#city');
            var state = $('#state');
            var zipcode = $('#postal_code');
            var billingAddress1 = $('#billing_address1');
            var billingAddress2 = $('#billing_address2');
            var billingCity = $('#billing_city');
            var billingState = $('#billing_state');
            var billingZipcode = $('#billing_postal_code');

            var firstName   = $('#first_name');
            var lastName = $('#last_name');
            var email = $('#email');

            var checkContactFields = $('.check-contact');
            var submitButton = $('#submit_button');

            checkContactFields.blur(function(){
                if (isPersonName(firstName.val()) && isEmail(email.val()) && (lastName.val() == '' || isPersonName(lastName.val()))) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            first_name: firstName.val(),
                            last_name : lastName.val(),
                            email     : email.val()
                        },
                        type: "POST",
                        url: "{{ route('ajax_check_if_contact_exists') }}",
                        beforeSend: function (request){
                            submitButton.prop('disabled', true);
                        },
                        complete: function (){
                            submitButton.prop('disabled', false);
                        },
                        success: function (response){
                            if (response.success) {
                                if (response.contacts > 0) {
                                    let contacts = response.contacts;
                                    let html = response.html;

                                    $('#existingContactsModalContent').html(html);
                                    $('#existingContactsModal').modal('show');
                                }
                            } else {
                                // uiAlert({type: 'error', title: 'Error', text: response.message});
                            }
                        },
                        error: function (data){
                            submitButton.prop('disabled', false);
                            console.log(data);
                        }
                    });
                }
            });

            contactTypeIdSelect.on('change', function(){
                let select = $(this);
                let contactTypeId = select.val();
                let opctionSeledted = select.find('option:selected');
                let contactTypeEntityType = opctionSeledted.data('entity_type');

                if (contactTypeEntityType === 'person') {
                    personFirstNameFieldsContainer.removeClass('hidden');
                    personFirstNameInputField.attr('name', 'first_name');

                    personLastNameFieldsContainer.removeClass('hidden');

                    companyNameFieldContainer.addClass('hidden');
                    companyNameInputField.removeAttr('name');

                } else if (contactTypeEntityType === 'company') {
                    personFirstNameFieldsContainer.addClass('hidden');
                    personFirstNameInputField.removeAttr('name');

                    personLastNameFieldsContainer.addClass('hidden');
                    
                    companyNameFieldContainer.removeClass('hidden');
                    companyNameInputField.attr('name', 'first_name');
                }
            })

            $('#admin_form').validate({
                rules: {
                    contact_type_id: {
                        required: true,
                        positive: true
                    },
                    first_name: {
                        required  : true,
                        personName: true
                    },
                    last_name: {
                        required: false,
                        plainText: true
                    },
                    email: {
                        required: true,
                        email   : true
                    },
                    alt_email: {
                        required: false,
                        email   : true
                    },
                    phone: {
                        required: true,
                        phone   : true
                    },
                    alt_phone: {
                        required: false,
                        phone   : true
                    },
                    address1: {
                        required : true,
                        plainText: true
                    },
                    address2: {
                        required: function(element) {
                            return $('#same_billing_address').prop('checked') && $('#billing_address2').val() != '';
                        },
                        plainText: true
                    },
                    city: {
                        required : true,
                        plainText: true
                    },
                    county: {
                        required: true,
                        plainText: true
                    },
                    state: {
                        required : true,
                        plainText: true
                    },
                    postal_code: {
                        required: true,
                        zipCode : true
                    },
                    billing_address1: {
                        required : true,
                        plainText: true
                    },
                    billing_address2: {
                        required: function(element) {
                            return $('#same_billing_address').prop('checked') && $('#address2').val() != '';
                        },
                        plainText: true
                    },
                    billing_city: {
                        required : true,
                        plainText: true
                    },
                    billing_state: {
                        required : true,
                        plainText: true
                    },
                    billing_postal_code: {
                        required: true,
                        zipCode : true
                    },
                    /*
                    lead_source: {
                        required: function(element) {
                            return isLead.prop('checked');
                        },
                        positive: true
                    },
                    assigned_to: {
                        required: function(element) {
                            return isLead.prop('checked');
                        },
                        positive: true
                    },
                     */
                    note: {
                        required : false,
                        plainText: true
                    }
                },
                messages: {
                    first_name: {
                        required : "@lang('translation.field_required')",
                        personName: "@lang('translation.invalid_entry')"
                    },
                    last_name: {
                        required : "@lang('translation.field_required')",
                        personName: 'Invalid last name.'
                    },
                    email: {
                        required: "@lang('translation.field_required')",
                        email   : 'Invalid email.'
                    },
                    alt_email: {
                        email   : 'Invalid email.'
                    },
                    phone: {
                        required: "@lang('translation.field_required')",
                        phone   : 'Invalid phone.'
                    },
                    alt_phone: {
                        phone   : 'Invalid phone.'
                    },
                    contact_type_id: {
                        required: "@lang('translation.field_required')",
                        positive: 'Please, select type.'
                    },
                    address1: {
                        required : "@lang('translation.field_required')",
                        plainText: 'Invalid address.'
                    },
                    address2: {
                        required : 'This field is required when Billing Address 2 is defined.',
                        plainText: 'Invalid address.'
                    },
                    city: {
                        required : "@lang('translation.field_required')",
                        plainText: 'Invalid city.'
                    },
                    state: {
                        required : "@lang('translation.field_required')",
                        plainText: 'Invalid state.'
                    },
                    postal_code: {
                        required: "@lang('translation.field_required')",
                        zipCode : 'Invalid zip code.'
                    },
                    billing_address1: {
                        required : "@lang('translation.field_required')",
                        plainText: 'Invalid address.'
                    },
                    billing_address2: {
                        required : 'This field is required when Address 2 is defined.',
                        plainText: 'Invalid address.'
                    },
                    billing_city: {
                        required : "@lang('translation.field_required')",
                        plainText: 'Invalid city.'
                    },
                    billing_state: {
                        required : "@lang('translation.field_required')",
                        plainText: 'Invalid state.'
                    },
                    billing_postal_code: {
                        required: "@lang('translation.field_required')",
                        zipCode : 'Invalid zip code.'
                    },
                    /*
                    lead_source: {
                        required: 'This field is required because "Is Lead" is checked',
                        positive: 'Please, select lead source.'
                    },
                    assigned_to: {
                        required: 'This field is required because "Is Lead" is checked',
                        positive: 'Please, select assigned to.'
                    },
                    */
                    note: {
                        plainText   : 'Invalid text.'
                    }
                },
                submitHandler: function(form){
                    let errors = false;

                    if (!errors) {
                        if (sameBillingAddress.prop('checked')) {
                            billingAddress1.prop('disabled', false);
                            billingAddress2.prop('disabled', false);
                            billingCity.prop('disabled', false);
                            billingState.prop('disabled', false);
                            billingZipcode.prop('disabled', false);
                        }

                        form.submit();
                    }
                }
            });

            /*
            isLead.change(function(){
                let el = $(this);
                let leadSources = $('.lead-source');

                if (el.prop('checked')) {
                    leadSources.removeClass('hidden');
                } else {
                    leadSources.addClass('hidden');
                    leadSources.find('select').val(0);
                }
            });
            */

            //  address2 city state postal_code

            // billing_address1 billing_address2 billing_city state postal_code  sameBillingAddress

            sameBillingAddress.change(function(){
                let el = $(this);

                if (el.prop('checked')) {
                    billingAddress1.val(address1.val()).prop('disabled', true);
                    billingAddress2.val(address2.val()).prop('disabled', true);
                    billingCity.val(city.val()).prop('disabled', true);
                    billingState.val(state.val()).prop('disabled', true);
                    billingZipcode.val(zipcode.val()).prop('disabled', true);
                } else {
                    billingAddress1.prop('disabled', false);
                    billingAddress2.prop('disabled', false);
                    billingCity.prop('disabled', false);
                    billingState.prop('disabled', false);
                    billingZipcode.prop('disabled', false);
                    /*
                    billingAddress1.prop('disabled', false);
                    billingAddress2.prop('disabled', false);
                    billingCity.prop('disabled', false);
                    billingState.prop('disabled', false);
                    billingZipcode.prop('disabled', false);
                    */
                }
            });

            $('#cancel_button').click(function(){
                if ("{{ $returnTo }}" !== "") {
                    window.location = "{{ $returnTo }}";
                } else {
                    window.location = "{{ route('contact_list') }}";
                }
            });


        });
    </script>
@stop

