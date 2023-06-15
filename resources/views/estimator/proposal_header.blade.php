<table class="table-centered table-light w-100 font-size-18">
    <tr>
        <td class="w-50 p8">
            @lang('translation.proposalname'): {{ $proposal->name }}
            <p class="mb0">Client: {!! $contact->full_name !!}</p>
            <p class="mb0">Created On: {{ $proposal->proposal_date->format('m/d/yy') }}</p>
        </td>
        <td class="w-50 p8">
            Client Primary Location:
            <p class="mb0">{{ $contact->FullAddressOneLine }}</p>
        </td>
    </tr>
    <tr>
        <td class="w-50 p8">
           Service Category:{{$proposalDetail->services_id}}
            {{ $service_category_name }} - Service: {{$proposalDetail->services_id}} - Category: {{$service->service_category_id}}
         <br/>
            Service Title: 
            {{ $proposalDetail->service_name }}
          </td>
        <td class="w-50 p8">
            Service Location:
            <p class="mb0">{!! $proposalDetail->location->full_location_two_lines !!}</p>
            <span id="changelocation" class="{{ $site_button_class }}">Change Location</span>
        </td>
    </tr>
</table>

