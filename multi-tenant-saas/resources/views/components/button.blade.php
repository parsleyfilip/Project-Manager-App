@props(['variant' => 'primary', 'href' => null])

@php
$baseClasses = 'inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500';
$variants = [
    'primary' => 'border-transparent text-black bg-indigo-600 hover:bg-indigo-700',
    'secondary' => 'border-gray-300 text-black bg-white hover:bg-gray-50',
];
$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
$tag = $href ? 'a' : 'button';
$attrs = $href ? ['href' => $href] : [];
@endphp

<{{ $tag }} {{ $attributes->merge(array_merge(['type' => 'button', 'class' => $classes], $attrs)) }}>
    {{ $slot }}
</{{ $tag }}>