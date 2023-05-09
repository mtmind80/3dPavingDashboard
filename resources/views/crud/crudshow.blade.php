@extends('layouts.master')

@section('content')
    <div>


        <table class="table table-bordered">
            <tr>

                @foreach ($columns as $column)
                    <td>{{ $column }}</td>
                @endforeach
            </tr>

            @if ($p)
                @foreach ($datum as $data)
                    <tr>
                        @foreach ($data->toArray() as $column => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                @foreach ($datum as $data)
                    <tr>
                        @foreach ($data as $d)
                            <td>{{ $d }}</td>
                        @endforeach
                    </tr>
                @endforeach

            @endif
        </table>


    </div>
    @if ($p)
        {{ $datum->links() }}
    @endif
@endsection
