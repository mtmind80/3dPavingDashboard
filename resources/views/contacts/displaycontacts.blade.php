@extends('layouts.master')

@section('title') Starter page @endsection

@section('content')
    @component('components.breadcrumb')
        @slot('title') Contacts @endslot
        @slot('li_1') <a href="/dashboard">Home</a> @endslot
        @slot('li_2') Contacts @endslot
    @endcomponent

    {{ $contacts->links() }}
    <div >

        <table class="table table-bordered">

            @foreach ($contacts as $data)
                <tr>
                    @foreach ($data->toArray() as $column => $value)
                        <td>{{ $column }}</td>
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>


    </div>
    {{ $contacts->links() }}

    
@endsection

    
