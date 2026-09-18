@extends('mail.layout')
@section('title', 'Transaction')
@section('preheader', 'Transaction')

@section('content')
    <div>
        <h1>Transaction</h1>
        @foreach ($fields as $field)
            @if (isset($data[$field]))
                <div>
                    <b>{{ $field }}: </b>{{ $data[$field] }}
                </div>
            @endif
        @endforeach
    </div>
@endsection
