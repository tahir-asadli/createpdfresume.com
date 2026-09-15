@extends('mail.layout')
@section('title', __('Your resume is ready!'))
@section('preheader', __('Dear :fullname, your resume is ready', ['fullname' => $resume->user->name]))

@section('content')
    <div>
        <h1>{{ __('Hello, :name', ['name' => $resume->user->name]) }}</h1>
        <p>
            {{ __('Your resume is ready and attached to the email') }}
        </p>
    </div>
@endsection
