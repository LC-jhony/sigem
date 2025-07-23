<x-layout>
    <div>
        <span style="font-weight: bold; font-size: 14px;">Vehiculo: </span>
        {{ $record->placa }}
    </div>
    <br>
    <table width="100%">
        <thead style="background-color: lightgray; font-size: 12px;">
            <tr>
                <th>Descripcion</th>
                <th>Kilometraje</th>
                <th>Precio Material</th>
                <th>Mano de Obra</th>
                <th>Costo Mantenimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->maintenances as $item)
            <tr>
                <td>{{ $item->maintenanceItem->name }}</td>
                <td>{{ $item->mileage }}</td>
                <td style="  text-align: right;">{{ $item->Price_material }}</td>
                <td style="  text-align: right;">{{ $item->workforce }}</td>
                <td style="  text-align: right;">{{ $item->maintenance_cost }}</td>
            </tr>
            @endforeach
        </tbody>
        @php
        $total = $record->maintenances->sum('maintenance_cost');
        @endphp
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total general:</strong></td>
                <td style="text-align: right;"><strong>S/. {{ number_format($total , 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</x-layout>
