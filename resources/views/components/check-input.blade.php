@props(['disabled' => false])

<div class="form-check form-switch">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([ 'role' => 'switch', 'class' => 'form-check-input']) !!}>
</div>