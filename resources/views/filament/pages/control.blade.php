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
            <tr>
                <x-td>1</x-td>
                <x-td>2</x-td>
                <x-td>3</x-td>
                <x-td>4</x-td>
                <x-td>5</x-td>
                <x-td>6</x-td>
                <x-td>7</x-td>
                <x-td>8</x-td>
                <x-td>9w</x-td>
                <x-td>10</x-td>
                <x-td>11</x-td>
                <x-td>12</x-td>
                <x-td>13</x-td>
                <x-td>14</x-td>
            </tr>
        </x-table>
    </x-container>
</x-filament-panels::page>
