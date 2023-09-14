<!DOCTYPE html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('layouts.print_head')

    <style type="text/css">
        @font-face {
            font-family: Pacifico;
            src: url('{{ public_path('fonts/Pacifico-Regular.tff') }}');
        }
    </style>
</head>

&nbsp;<p>&nbsp;</p>
&nbsp;<p>&nbsp;</p>
<table>
    <thead>
    <tr>
    <th class="headline">Proposal</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><h2>{{$proposal['name']}}</h2></td>
    </tr>
    <tr>
        <td class="normaltext">{!!$proposal['location']['full_location_two_lines']!!}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><h3>Prepared For</h3></td>
    </tr>
    <tr>
        <td class="normaltext">{{$proposal['contact']['first_name']}}</td>
    </tr>
    <tr>
        <td>
            @php
                $address = \App\Models\Location::where('id', '=', $proposal['contact']['id'])->first()->toArray();
            @endphp
            {!!$address['full_location_two_lines']!!}

        </td>
    </tr>
    <tr>
        <td>{{$proposal['contact']['email']}}</td>
    </tr>

    </tbody>
</table>

<!-- Service Listings -->
<p class="pb">
</p>
<h2>Service Listings</h2>
<table>

          @php
              $totalcost = 0;
          @endphp
    @foreach ($services as $service)
        @php
            $totalcost += $service->cost;
        @endphp

        <tr>
        <td class="tl fw-bolder">{{$service->service_name}}</td>
        <td class="tr fw-bolder">{{ \App\Helpers\Currency::format($service->cost ?? '0.0') }}
        </td>
        </tr>
        <tr>
        <td colspan='2' class="tl">{!!$service->proposal_text!!} </td>
        </tr>
@endforeach
</table>
<p class="pb">
</p>
<h3>Service Summary</h3>
<table>
@foreach ($services as $service)
    <tr>
        <td class="small_normal tl">
            {{$service->service_name}}
        </td>
        <td class="small_normal tl">
            {{ \App\Helpers\Currency::format($service->cost ?? '0.0') }}
        </td>
    </tr>


@endforeach
    <tr class='totalcolor'>
    <td class="small_normal tl">
        Total
    </td>
    <td class="small_normal tl">
    {{$currency_total_details_costs}}
    </td>
    </tr>
</table>
<p class="pb">
</p>
<div class="headerclass">Acceptances of proposal</div>
<br/>
<br/>
    We would like to thank you for the opportunity to visit your property and the possibility to earn your
business. We are committed to providing our customers with great service and workmanship on all
of our projects. Our commitment to customers is why we always warranty our projects and stand
behind our work.
<br/>
<br/>
    To proceed with our proposal please execute below and return to 3-D Paving and Sealcoating, LLC
via e-mail. Upon execution this proposal becomes a binding contract. Customer acknowledges it
has read this entire document including "General Terms and Conditions" and "Service Terms and
Conditions".
<br/>
<br/>
    Payment Terms: 40% Deposit Due Upon Signed Contract, 60% Due Upon Completion.
This proposal expires thirty (30) days from the date hereof, but may be accepted at any later date
at the sole option of 3-D Paving.
    <br/>
    <table class="w-100">
        <tr class='totalcolor'>
            <td class="tl">
                Services Estimated Total
            </td>
            <td class="tl">
                {{$currency_total_details_costs}}
            </td>
        </tr>
        <tr>
        </table>
<table style="width:100%;border:2px;">
    <td class="PavingSignature tl">
        <div>Managers</div>

    {{$sales['fname']}} {{$sales['lname']}}
    </td>
    <td class="tl">Client
    </td>
    </tr>
</table>
<p class="pb">
</p>
<h1>General Terms</h1>
<table>
    <tr>
        <td class="small_normal tl">
        @foreach($terms as $term)
            @if($loop->even && $loop->index > 1)
                </td><td class="small_normal tl">
            @endif
            <strong style="color:#585657;"><u>{{$term['title']}}:</u></strong> <br /><br />
            {!!$term['text']!!}
        @endforeach
        </td>
    </tr>
</table>

<p class="pb">
</p>


<h1>Service Terms and Conditions</h1>
<table>
    <tr>
        <td class="small_normal tl">
            @foreach($ServiceTerms as $sterm)
                @if($loop->index ==2 )
                     </td><td class="small_normal tl">
                @endif
            <strong style="color:#585657;"><u>{{$sterm['title']}}:</u></strong> <br />
            {!!$sterm['text']!!}
            @endforeach
        </td>
    </tr>
</table>


</body>
</html>
