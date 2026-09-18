@extends('mail.layout')
@section('title', __('Your subscription has been renewed'))
@section('preheader', __('Dear :fullname, your subscription has been renewed', ['fullname' => $user->name]))

@section('content')
    <div>
        <h1>{{ __('Dear') }}, {{ $user->name }}</h1>
        <p>
            {{ __('Your subscription has been renewed') }}: {{ now()->format('d/m/Y') }}
        </p>
        <p>
            <b>{{ __('Subscription plan') }}</b>: {{ $subscription->plan_name }}
        </p>
        <p>
            <b>{{ __('Subscription price') }}</b>: {{ $subscription->priceStrUSDAZN() }}
        </p>
        <p>
            <b>{{ __('Subscription end date') }}</b>: {{ $subscription->ends_at->format('d/m/Y') }}
        </p>
        <p>
            <b>{{ __('Subscription will renew') }}</b>: {{ $subscription->renew ? __('Yes') : __('No') }}
        </p>
        <p>
            <a href="{{ route('subscription') }}">{{ __('Cancel subscription') }}</a>
        </p>
    </div>
@endsection
