<!-- create drop downlist with default values set -->
<select class="form-control" name="{{ $name }}" id="{{ $name }}">
    @php
        $modelname = 'App\\Models\\' . $model;
        if($distinct)
            {
                $records = $modelname::select($field)->distinct()->orderBy($field)->get();
                
            } else {
            
                $records = $modelname::all()->sortBy("id");
            }
    @endphp
    @foreach($records as $record)
        @if($distinct)
            <option value="{{$record->$field}}" @if($default == "$record->$field") selected @endif>{{$record->$field}}</option>
        @else
            <option value="{{$record->id}}" @if($default == "$record->id") selected @endif>{{$record->$value}}</option>
        @endif
    @endforeach

</select>
    
