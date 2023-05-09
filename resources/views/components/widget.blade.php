<!-- create drop downlist with default values set -->
<select class="form-control" name="{{ $name }}" id="{{ $name }}">
    @php
        $modelname = 'App\\Models\\' . $model;
        $records = $modelname::all()->sortBy("id");
    @endphp
    @foreach($records as $record)
        <option value="{{$record->id}}" @if($default == "$record->id") selected @endif>{{$record->$value}}</option>
    @endforeach

</select>
    
