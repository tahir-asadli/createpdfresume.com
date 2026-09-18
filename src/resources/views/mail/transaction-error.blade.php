@extends('mail.layout')
@section('title', 'Transaction error')
@section('preheader', 'Transaction error')

@section('content')
    <div>
        <h1>Transaction error</h1>
        <div>endpoint: {{ $endpoint }}</div>
        <div>description: {{ $description }}</div>
        <div>error: {{ $error }}</div>
        <div>user: {{ $user ? $user->id : 'no user' }}</div>
        <div>orderId: {{ $orderId }}</div>
        <div>response: {{ $response ? print_r($response) : 'no reposen' }}</div>
    </div>
@endsection
