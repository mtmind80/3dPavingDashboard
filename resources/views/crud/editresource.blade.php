@extends('layouts.master')

@section('content')
    <div>


        @if($records)
            <table class="table table-bordered">
                @foreach ($records as $key => $value)
                    @if (in_array(@key,$columns))
                        <tr>
                            <td>{{ $key }}</td>
                            <td><input type="text" value="{{ $value }}"></td>
                        </tr>
                    @endif
                @endforeach

            </table>
        @else
            Hello
        @endif


    </div>

@endsection
