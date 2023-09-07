<!DOCTYPE html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('layouts.print_head')

</head>
<body>
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
Service Listings
<!-- Service Summary -->
<p class="pb">
</p>
Service Summary
<!-- Service Disclaimers -->
<p class="pb">
</p>
Signature Page
<!-- General Terms -->
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
<!-- Service Terms -->
<p class="pb">
</p>

<h1>Service Terms and Conditions</h1>
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
</body>
</html>
