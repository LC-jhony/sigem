<x-layout>
    {{ $vehicle->placa }} - {{ $vehicle->brand }} - {{ $vehicle->model }} - {{ $vehicle->year }}
    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th>Descripcion</th>
                <th>KM</th>
                <th>Precio Material</th>
                <th>Mano de Obra</th>
                <th>Costo Mantenimiento</th>

            </tr>
        </thead>
        <tbody>
            @foreach($records as $item)
            <tr>
                <td>{{ $item->maintenanceItem->name }}</td>
                <td>{{ $item->mileage }}</td>
                <td style="text-align: right;">{{ $item->Price_material }}</td>
                <td style="text-align: right;">{{ $item->workforce }}</td>
                <td style="text-align: right;">{{ $item->maintenance_cost }}</td>
            </tr>
            @endforeach
        </tbody>
        @php
        $total = collect($records)->sum('maintenance_cost');
        @endphp
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total general:</strong></td>
                <td style="text-align: right;"><strong>S/. {{ number_format($total , 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>


</x-layout>
