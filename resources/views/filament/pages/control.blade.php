<x-filament-panels::page>
    <x-container>
        <x-table>
            <x-slot name="header">
                <!-- Header Row 1 -->
                <tr>
                    <x-th colspan="5">
                        Supervisión y Control de Mantenimiento
                    </x-th>
                    <x-th rowspan="2">
                        Tarjeta de Propiedad
                    </x-th>
                    <x-th colspan="2">
                        SOAT
                    </x-th>
                    <x-th colspan="2">
                        Tarjeta de Circulación
                    </x-th>
                    <x-th colspan="2">
                        Revisión Técnica
                    </x-th>
                    <x-th colspan="2">
                        Póliza de Seguro Vehicular
                    </x-th>
                </tr>

                <!-- Header Row 2 -->
                <tr>
                    <x-th colspan="3">
                        Fecha
                    </x-th>
                    <x-th class="px-3 py-2"></x-th>
                    <x-th class="px-3 py-2"></x-th>
                    <x-th colspan="2">
                        Vencimiento
                    </x-th>
                    <x-th colspan="2">
                        Vencimiento
                    </x-th>
                    <x-th colspan="2">
                        Vencimiento
                    </x-th>
                    <x-th colspan="2">
                        Vencimiento
                    </x-th>
                </tr>

                <!-- Column Headers -->
                <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75 hover:bg-gray-50 dark:hover:bg-white/5">

                    <x-th>COD</x-th>
                    <x-th>PROG.</x-th>
                    <x-th>PLACA</x-th>
                    <x-th>MARCA</x-th>
                    <x-th>UNIDAD</x-th>
                    <x-th>AÑO</x-th>
                    <x-th>Fecha Vencimiento</x-th>
                    <x-th>Vence en Días</x-th>
                    <x-th>Fecha Vencimiento</x-th>
                    <x-th>Vence en Días</x-th>
                    <x-th>Fecha Vencimiento</x-th>
                    <x-th>Vence en Días</x-th>
                    <x-th>Fecha Vencimiento</x-th>
                    <x-th>Vence en Días</x-th>
                </tr>

            </x-slot>
            @foreach($vehicles as $index => $vehicle)
            <tr class="fi-ta-row transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5 
                       {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }}">
                <x-td>{{ $vehicle->id }}</x-td>
                <x-td>{{ $vehicle->program }}</x-td>
                <x-td>{{ $vehicle->placa }}</x-td>
                <x-td>{{ $vehicle->marca }}</x-td>
                <x-td>{{ $vehicle->modelo }}</x-td>
                <x-td>{{ $vehicle->year }}</x-td>
                {{-- SOAT --}}
                <x-td>{{ $vehicle->documents->where('name', 'SOAT')->first()?->date ?? '' }}</x-td>
                <x-td>{{ $vehicle->days_to_expire_soat }}</x-td>

                {{-- TARJETA DE CIRCULACION --}}
                <x-td>{{ $vehicle->documents->where('name', 'TARJETA DE CIRCULACION')->first()?->date ?? '' }}</x-td>
                <x-td>{{ $vehicle->days_to_expire_circulation }}</x-td>

                {{-- REVISION TECNICA --}}
                <x-td>{{ $vehicle->documents->where('name', 'REVICION TECNICA')->first()?->date ?? '' }}</x-td>
                <x-td>{{ $vehicle->days_to_expire_technical }}</x-td>

                {{-- POLIZA DE SEGURO VEHICULAR --}}
                <x-td>{{ $vehicle->documents->where('name', 'POLIZA DE SEGURO VEHICULAR')->first()?->date ?? '' }}</x-td>
                <x-td>{{ $vehicle->days_to_expire_property }}</x-td>
            </tr>
            @endforeach
        </x-table>
    </x-container>
</x-filament-panels::page>
