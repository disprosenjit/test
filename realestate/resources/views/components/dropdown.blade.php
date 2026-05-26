@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'bg-white'])

@php
$alignClasses = match($align) {
    'left' => 'left-0 origin-top-left',
    default => 'right-0 origin-top-right',
};

$widthClass = match($width) {
    '48' => 'w-48',
    default => 'w-48',
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $widthClass }} {{ $alignClasses }} rounded-xl shadow-lg border border-slate-200 {{ $contentClasses }}"
         style="display: none;">
        {{ $content }}
    </div>
</div>
