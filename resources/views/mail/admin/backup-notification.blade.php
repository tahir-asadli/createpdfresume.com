@extends('mail.layout')
@section('title', 'Backup Notification!')
@section('preheader', 'Backup Notification')

@section('content')
    <div>
        <h1>Backup Notification</h1>
        <h3>Backup created at: {{ $date }}</h3>
        <h3>File name: {{ $fileName }}</h3>

    </div>
@endsection
