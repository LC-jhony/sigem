@php
$intervals = $getIntervals();
$activities = $getActivities();
$vehicle = $getVehicle();
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300">
        <thead>
            <tr>
                <th rowspan="2" class="border border-gray-300 px-2 py-1 bg-gray-100">UNIDADES</th>
                <th rowspan="2" class="border border-gray-300 px-2 py-1 bg-gray-100">DESCRIPCIÓN</th>
                <th rowspan="2" class="border border-gray-300 px-2 py-1 bg-gray-100">TIPO / KM DE SERV.</th>
                <th colspan="{{ count($intervals) }}" class="border border-gray-300 px-2 py-1 bg-gray-100 text-center">KM DE SERVICIO</th>
            </tr>
            <tr>
                @foreach($intervals as $interval)
                <th class="border border-gray-300 px-2 py-1 bg-gray-100 text-center">
                    {{ number_format($interval) }} km
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td class="border border-gray-300 px-2 py-1">
                    @if($loop->first)
                    {{ $vehicle->unidad }}
                    @endif
                </td>
                <td class="border border-gray-300 px-2 py-1">
                    {{ $activity['description'] }}
                </td>
                <td class="border border-gray-300 px-2 py-1 text-center">
                    {{ $activity['type'] }}
                </td>

                @foreach($intervals as $interval)
                <td class="border border-gray-300 px-2 py-1 text-center">
                    <input type="checkbox" name="activities[{{ $activity['id'] }}][{{ $interval }}]" @checked(old("activities.{$activity['id']}.{$interval}", $activity['intervals'][$interval] ?? false)) class="rounded text-primary-600 focus:ring-primary-500" wire:model.defer="activities.{{ $activity['id'] }}.{{ $interval }}">
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
