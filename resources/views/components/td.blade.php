@props(['align' => 'left'])

@php
$textAlignClass=[
'left' => 'text-left',
'light' => 'text-right',
'center' => 'text-center',
][$align] ?? 'text-left';
@endphp

<td {{ $attributes->merge([
        'class' => "px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-center {$textAlignClass}"
    ]) }}>
    {{ $slot }}
</td>
