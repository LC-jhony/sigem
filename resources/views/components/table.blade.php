{{--
    Custom Table Component
    Inherits Filament PHP styles (fi-ta-table) and custom theme styles (es-table)
    Combines both design systems for consistent styling
--}}

<table class="fi-ta-table  w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
    <thead class="divide-y divide-gray-200 dark:divide-white/5">
        {{ $header}}
    </thead>

    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
        {{ $slot }}
    </tbody>
</table>
