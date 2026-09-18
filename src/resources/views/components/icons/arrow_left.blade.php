@props(['width' => '20', 'height' => '20'])
<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
    xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge() }} height="{{ $height }}" width="{{ $width }}">
    <line x1="19" y1="12" x2="5" y2="12"></line>
    <polyline points="12 19 5 12 12 5"></polyline>
</svg>
