<x-layout>
    <table width="100%">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th colspan="5">Supervisión y Control de Mantenimiento</th>
                <th>TARJETA DE <br> PROPIEDAD</th>
                <th colspan="2">SOAT</th>
                <th colspan="2">TARJETA DE CIRCULACION </th>
                <th colspan="2">REVICION TECNICA </th>
                <th colspan="2">POLIZA DE SEGURO VEHICULAR</th>
            </tr>
            <tr>
                <th colspan="3">FECHA</th>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="2">VENCIMIENTO</th>
                <th colspan="2">VENCIMIENTO</th>
                <th colspan="2">VENCIMIENTO</th>
                <th colspan="2">VENCIMIENTO</th>
            </tr>
            <tr>
                <th>COD</th>
                <th>PROG.</th>
                <th>PLACA</th>
                <th>MARCA</th>
                <th>UNIDAD</th>
                <th>AÑO</th>
                <th>Fecha<br> Vencimiento</th>
                <th>Vence en Días</th>
                <th>Fecha<br> Vencimiento</th>
                <th>Vence en Días</th>
                <th>Fecha<br> Vencimiento</th>
                <th>Vence en Días</th>
                <th>Fecha<br> Vencimiento</th>
                <th>Vence en Días</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
            @endphp
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td style="text-align: center;">{{ ++$i }}</td>
                    <td style="text-align: center;">{{ $vehicle->code }}</td>
                    <td style="text-align: center;">{{ $vehicle->placa }}</td>
                    <td style="text-align: center;">{{ $vehicle->marca }}</td>
                    <td style="text-align: center;">{{ $vehicle->unidad }}</td>
                    <td style="text-align: center;">{{ $vehicle->property_card }}</td>
                    <td style="text-align: center;">
                        @php
                            $soat = $vehicle->documents->firstWhere('name', 'SOAT');
                            $soatDate = $soat ? \Carbon\Carbon::parse($soat->date)->setTimezone('America/Lima') : null;
                            $soatClass = '';
                            $soatDias = '';
                            if ($soatDate) {
                                // Calcula días desde hoy hasta la fecha de vencimiento con horario peruano
                                $dias = now()->setTimezone('America/Lima')->diffInDays($soatDate);
                                $soatDias = (int) $dias;

                                if ($dias < 0) {
                                    $soatClass = 'expired';
                                } elseif ($dias <= 7) {
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
                    <td style="text-align: center;">
                        <span class="{{ $soatClass }}">
                            {{ $soatDias !== '' ? $soatDias : '-' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $circulacion = $vehicle->documents->firstWhere('name', 'TARJETA DE CIRCULACION');
                            $circulacionDate = $circulacion
                                ? \Carbon\Carbon::parse($circulacion->date)->setTimezone('America/Lima')
                                : null;
                            $circulacionClass = '';
                            $circulacionDias = '';
                            if ($circulacionDate) {
                                $dias = now()->setTimezone('America/Lima')->diffInDays($circulacionDate);
                                $circulacionDias = (int) $dias;

                                if ($dias < 0) {
                                    $circulacionClass = 'expired';
                                } elseif ($dias <= 7) {
                                    $circulacionClass = 'expired';
                                } elseif ($dias <= 30) {
                                    $circulacionClass = 'warning';
                                } else {
                                    $circulacionClass = 'valid';
                                }
                            }
                        @endphp
                        <span class="{{ $circulacionClass }}">
                            {{ $circulacionDate ? $circulacionDate->format('d/m/Y') : 'Sin Tarjeta' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="{{ $circulacionClass }}">
                            {{ $circulacionDias !== '' ? $circulacionDias : '-' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $revision = $vehicle->documents->firstWhere('name', 'REVICION TECNICA');
                            $revisionDate = $revision
                                ? \Carbon\Carbon::parse($revision->date)->setTimezone('America/Lima')
                                : null;
                            $revisionClass = '';
                            $revisionDias = '';
                            if ($revisionDate) {
                                $dias = now()->setTimezone('America/Lima')->diffInDays($revisionDate);
                                $revisionDias = (int) $dias;

                                if ($dias < 0) {
                                    $revisionClass = 'expired';
                                } elseif ($dias <= 7) {
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
                    <td style="text-align: center;">
                        <span class="{{ $revisionClass }}">
                            {{ $revisionDias !== '' ? $revisionDias : '-' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @php
                            $poliza = $vehicle->documents->firstWhere('name', 'POLIZA DE SEGURO VEHICULAR');
                            $polizaDate = $poliza
                                ? \Carbon\Carbon::parse($poliza->date)->setTimezone('America/Lima')
                                : null;
                            $polizaClass = '';
                            $polizaDias = '';
                            if ($polizaDate) {
                                $dias = now()->setTimezone('America/Lima')->diffInDays($polizaDate);
                                $polizaDias = (int) $dias;

                                if ($dias < 0) {
                                    $polizaClass = 'expired';
                                } elseif ($dias <= 7) {
                                    $polizaClass = 'expired';
                                } elseif ($dias <= 30) {
                                    $polizaClass = 'warning';
                                } else {
                                    $polizaClass = 'valid';
                                }
                            }
                        @endphp
                        <span class="{{ $polizaClass }}">
                            {{ $polizaDate ? $polizaDate->format('d/m/Y') : 'Sin Póliza' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="{{ $polizaClass }}">
                            {{ $polizaDias !== '' ? $polizaDias : '-' }}
                        </span>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>
