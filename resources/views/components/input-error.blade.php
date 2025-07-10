@props(['messages'])

@if ($messages)
    <small class="text-danger">{{ $messages }}</small>
    <?php /*<ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach */ ?>
    </ul>
@endif
