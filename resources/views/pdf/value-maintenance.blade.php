<x-layout>
    <x-slot name="head">
        <div class="report-title">
            <h1>VALORIZADO DE MANTENIMIENTO <br> VEHICULAR</h1>
            <div class="report-number">
                <strong style="font-widget: bold;"> Placa: </strong>
                {{ $vehicle->placa }}<BR>
                <strong style="font-widget: bold;"> Marca: </strong>
                {{ $vehicle->marca ?? '-' }} <BR>
                <strong style="font-widget: bold;"> Unidad: </strong>
                {{ $vehicle->unidad ?? '-' }}< <BR>
                <strong style="font-widget: bold;"> Tarjeta Pro.: </strong>
                {{ $vehicle->property_card ?? '-' }} <BR>
                {{-- <strong style="font-widget: bold;"> Mes: </strong>
                mes --}}
            </div>
        </div>
    </x-slot>
    <table class="table">
        <thead >
        <tr>
            <th>Descripcion</th>
            <th>KM</th>
            <th>Precio Material</th>
            <th>Mano de Obra</th>
            <th>Costo Mantenimiento</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($records as $item)
            <tr>
                <td>{{ $item->maintenanceItem->name }}</td>
                <td>{{ $item->mileage }}</td>
                <td style="text-align: right;">S/. {{ number_format($item->Price_material, 2) }}</td>
                <td style="text-align: right;">S/. {{ number_format($item->workforce, 2) }}</td>
                <td style="text-align: right;">S/. {{ number_format($item->maintenance_cost, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        @php
            $total = $records->sum('maintenance_cost');
        @endphp
        <tfoot>
        <tr>
            <td colspan="4" style="text-align: right; font-style: italic;"><strong>Total general:</strong></td>
            <td style="text-align: right;"><strong>S/. {{ number_format($total, 2) }}</strong></td>
        </tr>
        </tfoot>
    </table>

    @php
        $hasImages = $records->filter(function($item) {
            return $item->photo || $item->file;
        })->count() > 0;
    @endphp

    @if($hasImages)
        <!-- Salto de página para las imágenes -->
        <div style="page-break-before: always;"></div>
        <!-- Contenedor para las imágenes que ocupen toda la página -->
        <div style="width: 100%; text-align: center;">
            @foreach ($records as $item)
                @if ($item->photo)
                    <div style="margin-bottom: 20px;">
                        <img src="{{ storage_path('app/public/' . $item->photo) }}"
                             style="width: 90%; max-width: 500px; border: 1px solid #ccc; padding: 5px;">
                    </div>
                @endif

                @if ($item->file)
                    <div style="margin-bottom: 20px;">
                        <img src="{{ storage_path('app/public/' . $item->file) }}"
                             style="width: 90%; max-width: 500px; border: 1px solid #ccc; padding: 5px;">
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</x-layout>
