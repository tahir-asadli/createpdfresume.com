@props(['id', 'value' => ''])
<div class="switch">
    <input type="checkbox" {{ $value == 1 ? 'checked' : '' }} id="{{ $id }}" {{ $attributes }}>
    <label for="{{ $id }}"></label>
</div>
