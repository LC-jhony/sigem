<div class="space-y-4">
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $record->marca }} {{ $record->unidad }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Placa: {{ $record->placa }} | PROG: {{ $record->code }}
                </p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $record->status === 'Operativo' ? 'bg-green-100 text-green-800' : 
                       ($record->status === 'En Mantenimiento' ? 'bg-yellow-100 text-yellow-800' : 
                        'bg-red-100 text-red-800') }}">
                    {{ $record->status }}
                </span>
            </div>
        </div>
    </div>
    
    @livewire('mantenance.mantenace-table', ['record' => $record])
</div>
