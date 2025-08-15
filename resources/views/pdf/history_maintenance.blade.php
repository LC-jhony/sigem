<x-layout>
    <x-slot name="head">
        <div class="report-title">
            <h1>HISTORIAL DE MANTENIMIENTO</h1>
            <div class="report-number">
                <strong style="font-widget: bold;"> Placa: </strong>
                {{ $record->placa }} <BR>
                <strong style="font-widget: bold;"> Marca: </strong>
                {{ $record->marca ?? '-' }} <BR>
                <strong style="font-widget: bold;"> Unidad: </strong>
                {{ $record->unidad ?? '-' }}< <BR>
                    <strong style="font-widget: bold;"> Tarjeta Pro.: </strong>
                    {{ $record->property_card ?? '-' }} <BR>
                    {{-- <strong style="font-widget: bold;"> Mes: </strong>
                    mes --}}
            </div>
        </div>
    </x-slot>
        {{-- <div class="section">
        <div class="section-title">
            HISTORIAL DE MANTENIMIENTO VEHICULAR
        </div>
    </div> --}}
    <table class="table">
        <thead>
            <tr>
                <th colspan="24" style="padding: 8px;">
HISTORIAL DE MANTENIMIENTO VEHICULAR
                </th>
            </tr>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Descripción</th>
                @foreach ($mileages as $i => $km)
                    <th>{{ $i % 2 == 0 ? 'L' : 'M' }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($mileages as $km)
                    <th>{{ number_format($km) }}</th>
                @endforeach
            </tr>
        </thead>

        <body>
            @foreach ($maintenanceitems as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    @foreach ($mileages as $km)
                        @php
                            $key = $item->id . '_' . $km;
                            $hasMaintenance = isset($maintenanceMap[$key]);
                        @endphp
                        <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">
                            @if ($hasMaintenance)
                                <span style="color: #636363ff; font-weight: bold;">X</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </body>
    </table>

</x-layout>
