@props(['width' => '20', 'height' => '20'])
<svg class="wblock-handle group-hover:opacity-100" stroke="currentColor" fill="currentColor" stroke-width="0"
    viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge() }} height="{{ $height }}"
    width="{{ $width }}">
    <path
        d="M108,60A16,16,0,1,1,92,44,16,16,0,0,1,108,60Zm56,16a16,16,0,1,0-16-16A16,16,0,0,0,164,76ZM92,112a16,16,0,1,0,16,16A16,16,0,0,0,92,112Zm72,0a16,16,0,1,0,16,16A16,16,0,0,0,164,112ZM92,180a16,16,0,1,0,16,16A16,16,0,0,0,92,180Zm72,0a16,16,0,1,0,16,16A16,16,0,0,0,164,180Z">
    </path>
</svg>
