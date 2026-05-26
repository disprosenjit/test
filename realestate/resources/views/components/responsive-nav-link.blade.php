@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block py-2 px-4 text-blue-600 bg-blue-50 rounded-lg font-semibold'
    : 'block py-2 px-4 text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-lg font-medium transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
