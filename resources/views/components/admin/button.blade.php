@props([
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
])

<button type="{{ $type }}" {{ $attributes->class([
    'btn',
    'btn-' . $variant,
    $size ? 'btn-' . $size : null,
    'rounded-3',
]) }}>
    {{ $slot }}
</button>
