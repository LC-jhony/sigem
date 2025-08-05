<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asignaciones - {{ $mine->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 18px;
            font-weight: normal;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .info-item {
            flex: 1;
        }

        .info-item strong {
            color: #333;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-active {
            color: #28a745;
            font-weight: bold;
        }

        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .summary {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ASIGNACIONES DE CONDUCTORES</h1>
        <h2>{{ $mine->name }}</h2>
    </div>

    <div class="info-section">
        <div class="info-item">
            <strong>Mina:</strong> {{ $mine->name }}
        </div>
        <div class="info-item">
            <strong>Período:</strong> {{ $monthName }} {{ $year }}
        </div>
        <div class="info-item">
            <strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    @if($assignments->count() > 0)


        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Conductor</th>
                        <th>DNI</th>
                        <th>Cargo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                    
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $index => $assignment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $assignment->driver->full_name ?? 'N/A' }}</td>
                            <td>{{ $assignment->driver->dni ?? 'N/A' }}</td>
                            <td>{{ $assignment->driver->cargo->name ?? 'N/A' }}</td>
                            <td>{{ $assignment->start_date ? $assignment->start_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $assignment->end_date ? $assignment->end_date->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                {{ $assignment->status }}
                                {{-- <span class="{{ $assignment->status === 'Activo' ? 'status-active' : 'status-inactive' }}">
                                </span> --}}
                            </td>
                          
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="no-data">
            <p>No se encontraron asignaciones para el período seleccionado.</p>
            <p><strong>Mina:</strong> {{ $mine->name }}</p>
            <p><strong>Período:</strong> {{ $monthName }} {{ $year }}</p>
        </div>
    @endif


</body>
</html>