@props(['width' => '20', 'height' => '20'])
<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
    xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge() }} height="{{ $height }}" width="{{ $width }}">
    <rect width="10" height="6" x="7" y="9" rx="2"></rect>
    <path d="M22 20H2"></path>
    <path d="M22 4H2"></path>
</svg>
