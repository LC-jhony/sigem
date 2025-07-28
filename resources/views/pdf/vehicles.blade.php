<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Vehículos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #7f8c8d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
        }

        .status-inactive {
            color: #e74c3c;
            font-weight: bold;
        }

        .document-date {
            font-size: 10px;
            color: #7f8c8d;
        }

        .expired {
            color: #e74c3c;
            font-weight: bold;
        }

        .warning {
            color: #f39c12;
            font-weight: bold;
        }

        .valid {
            color: #27ae60;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Vehículos</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total de vehículos: {{ $vehicles->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>PROG.</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Unidad</th>
                <th>Tarjeta Propiedad</th>
                <th>Estado</th>
                <th>SOAT</th>
                <th>Tarjeta Circulación</th>
                <th>Revisión Técnica</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->code }}</td>
                    <td>{{ $vehicle->placa }}</td>
                    <td>{{ $vehicle->marca }}</td>
                    <td>{{ $vehicle->unidad }}</td>
                    <td>{{ $vehicle->property_card }}</td>
                    <td class="status-{{ strtolower($vehicle->status) }}">
                        {{ $vehicle->status }}
                    </td>
                    <td>
                        @php
                            $soat = $vehicle->documents->firstWhere('name', 'SOAT');
                            $soatDate = $soat ? \Carbon\Carbon::parse($soat->date) : null;
                            $soatClass = '';
                            if ($soatDate) {
                                $dias = now()->diffInDays($soatDate, false);
                                if ($dias < 0 || $dias <= 7) {
                                    $soatClass = 'expired';
                                } elseif ($dias <= 30) {
                                    $soatClass = 'warning';
                                } else {
                                    $soatClass = 'valid';
                                }
                            }
                        @endphp
                        <span class="{{ $soatClass }}">
                            {{ $soatDate ? $soatDate->format('d/m/Y') : 'Sin SOAT' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $tarjeta = $vehicle->documents->firstWhere('name', 'TARJETA DE CIRCULACION');
                            $tarjetaDate = $tarjeta ? \Carbon\Carbon::parse($tarjeta->date) : null;
                            $tarjetaClass = '';
                            if ($tarjetaDate) {
                                $dias = now()->diffInDays($tarjetaDate, false);
                                if ($dias < 0 || $dias <= 7) {
                                    $tarjetaClass = 'expired';
                                } elseif ($dias <= 30) {
                                    $tarjetaClass = 'warning';
                                } else {
                                    $tarjetaClass = 'valid';
                                }
                            }
                        @endphp
                        <span class="{{ $tarjetaClass }}">
                            {{ $tarjetaDate ? $tarjetaDate->format('d/m/Y') : 'Sin Tarjeta' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $revision = $vehicle->documents->firstWhere('name', 'REVICION TECNICA');
                            $revisionDate = $revision ? \Carbon\Carbon::parse($revision->date) : null;
                            $revisionClass = '';
                            if ($revisionDate) {
                                $dias = now()->diffInDays($revisionDate, false);
                                if ($dias < 0 || $dias <= 7) {
                                    $revisionClass = 'expired';
                                } elseif ($dias <= 30) {
                                    $revisionClass = 'warning';
                                } else {
                                    $revisionClass = 'valid';
                                }
                            }
                        @endphp
                        <span class="{{ $revisionClass }}">
                            {{ $revisionDate ? $revisionDate->format('d/m/Y') : 'Sin Revisión' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el sistema SIGEM</p>
        <p>Página 1</p>
    </div>
</body>

</html>
