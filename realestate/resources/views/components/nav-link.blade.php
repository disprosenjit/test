@props(['active'])

@php
$classes = ($active ?? false)
    ? 'text-blue-600 font-semibold text-sm'
    : 'text-slate-600 hover:text-blue-600 font-medium text-sm transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
