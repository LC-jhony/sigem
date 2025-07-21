@props(['align' => 'left', 'type' => 'body'])

@php
$textAlignClass = [
'left' => 'text-left',
'right' => 'text-right',
'center' => 'text-center',
][$align] ?? 'text-left';

// Different styles for header vs body cells to match Filament's design
$baseClasses = $type === 'header'
? "px-3 py-3 text-sm font-semibold text-gray-950 dark:text-white bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10"
: "px-3 py-4 text-sm text-gray-950 dark:text-white align-top";
@endphp

{{--
    Custom TD Component
    Inherits Filament PHP table cell styles (fi-ta-cell pattern) and custom theme styles
    Supports both header and body cell types with appropriate styling
--}}
<td {{ $attributes->merge([
        'class' => "{$baseClasses} {$textAlignClass}"
    ]) }}>
    {{ $slot }}
</td>
