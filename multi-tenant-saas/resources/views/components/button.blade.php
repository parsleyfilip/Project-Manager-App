@props(['variant' => 'primary', 'href' => null])

@php
$baseClasses = 'inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
$variants = [
    'primary' => 'border border-teal-200 text-gray-900 bg-teal-100 hover:bg-teal-200 focus:ring-teal-500',
    'secondary' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:ring-teal-500',
    'danger' => 'border border-red-200 text-gray-900 bg-red-100 hover:bg-red-200 focus:ring-red-500',
    'success' => 'border border-green-200 text-gray-900 bg-green-100 hover:bg-green-200 focus:ring-green-500',
    'warning' => 'border border-yellow-200 text-gray-900 bg-yellow-100 hover:bg-yellow-200 focus:ring-yellow-500',
];
$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
$tag = $href ? 'a' : 'button';
$attrs = $href ? ['href' => $href] : [];
@endphp

<{{ $tag }} {{ $attributes->merge(array_merge(['type' => 'button', 'class' => $classes], $attrs)) }}>
    {{ $slot }}
</{{ $tag }}>