<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Programa de Mantenimiento Vehicular</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0;
            font-size: 14px;
        }
        .vehicle-info {
            border: 1px solid #e0e0e0;
            padding: 10px;
            border-radius: 4px;
            background-color: #fdfdfd;
            margin-bottom: 15px;
        }
        .vehicle-info .title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
            color: #111;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        .vehicle-info div {
            margin-bottom: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .item-name {
            text-align: left;
            font-size: 7px;
            max-width: 120px;
        }
        .maintenance-mark {
            color: #636363;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>Programa de Mantenimiento Vehicular</h3>
    </div>
    
    <div class="vehicle-info">
        <div class="title">Vehículo</div>
        <div><strong>Placa:</strong> {{ $record->placa }}</div>
        <div><strong>Marca:</strong> {{ $record->marca ?? '-' }}</div>
        <div><strong>Unidad:</strong> {{ $record->unidad ?? '-' }}</div>
        <div><strong>Tarjeta Propiedad:</strong> {{ $record->property_card ?? '-' }}</div>
    </div>

    <table>
        <thead>
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
        <tbody>
            @foreach ($maintenanceitems as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td class="item-name">{{ $item->name }}</td>
                @foreach ($mileages as $km)
                <td>
                    @if($maintenanceMatrix[$item->id][$km])
                    <span class="maintenance-mark">X</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
