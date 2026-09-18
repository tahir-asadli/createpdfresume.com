@extends('mail.layout')
@section('title', 'Site Crash Alert!')
@section('preheader', 'An error occurred')

@section('content')
    <h1>Site Crash Alert</h1>
    <p><b>URL: </b>{{ $url }}</p>
    @if($user)
    <p><b>User: </b>{{ $user->name }}, {{  $user->email }}</p>
    @endif
    <p>An error occurred:</p>
    <pre><?php print_r($exception); ?></pre>
    <p>Check the application immediately.</p>
@endsection
