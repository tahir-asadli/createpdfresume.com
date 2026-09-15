@extends('mail.layout')
@section('title', 'Admin Məlumat!')
@section('preheader', 'Admin Məlumat')

@section('content')
    <div>
        <h1>Admin Məlumat: {{ now()->format('d/m/Y') }}</h1>
        @foreach ($array as $key => $items)
            <h2><b>{{ $key }}</b> </h2>
            @if (is_array($items))
                @foreach ($items as $k => $item)
                    <p><b>{{ $k }}: </b>
                        @if (is_array($item))
                            {{ implode(', ', $item) }}
                        @else
                            {{ $item }}
                        @endif
                    </p>
                @endforeach
            @else
                <p>{{ $items }}</p>
            @endif
        @endforeach
    </div>
@endsection
