<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center">
                <x-filament::icon icon="heroicon-o-information-circle" class="h-5 w-5 mr-2 text-info-500" />
                Instrucciones
            </div>
        </x-slot>

        <div class="space-y-3">
            <div class="flex items-start space-x-3">
                <x-filament::badge color="primary" class="mt-0.5">1</x-filament::badge>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Seleccione la mina, mes y año deseados en el formulario
                </p>
            </div>

            <div class="flex items-start space-x-3">
                <x-filament::badge color="info" class="mt-0.5">2</x-filament::badge>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Los datos se actualizarán automáticamente en el panel derecho
                </p>
            </div>

            <div class="flex items-start space-x-3">
                <x-filament::badge color="warning" class="mt-0.5">3</x-filament::badge>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Revise la información en la vista previa
                </p>
            </div>

            <div class="flex items-start space-x-3">
                <x-filament::badge color="success" class="mt-0.5">4</x-filament::badge>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Haga clic en "Exportar PDF" para descargar el reporte
                </p>
            </div>
        </div>
    </x-filament::section>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1">
            <!-- Form Section - Left Side -->
            <div class="space-y-6">        
                    {{ $this->form }}
            </div>
        </div>
        <div class="col-span-1 md:col-span-2">
            <!-- Preview Section - Right Side -->
            <div class="space-y-6 ">
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center">
                            <x-filament::icon icon="heroicon-o-eye" class="h-5 w-5 mr-2 text-primary-500" />
                            Vista Previa del Reporte
                        </div>
                    </x-slot>

                    @if ($selectedMine && $selectedMonth && $selectedYear)
                        <!-- Report Header -->
                       

                        <!-- Data Preview -->
                        @if (count($previewData) > 0)
                            <div class="mb-4">

                                <div
                                    class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                                    <div
                                        class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                                        <table
                                            class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                                            <thead class="divide-y divide-gray-200 dark:divide-white/5">
                                                <tr class="bg-gray-50 dark:bg-white/5">
                                                    <th
                                                        class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                                        <span
                                                            class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                                            <span
                                                                class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                                                Conductor
                                                            </span>
                                                        </span>
                                                    </th>
                                                    <th
                                                        class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                                        <span
                                                            class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                                            <span
                                                                class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                                                Fecha
                                                            </span>
                                                        </span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                                                @foreach ($previewData as $assignment)
                                                    <tr
                                                        class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                                        <td
                                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                                            <div class="fi-ta-col-wrp">
                                                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                                                    <div class="flex">
                                                                        <div
                                                                            class="fi-ta-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-950 dark:text-white">
                                                                            {{ $assignment['driver']['name'] ?? 'N/A' }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                                            <div class="fi-ta-col-wrp">
                                                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                                                    <div class="flex">
                                                                        <div
                                                                            class="fi-ta-text-item inline-flex items-center gap-1.5 text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                                            {{ \Carbon\Carbon::parse($assignment['created_at'])->format('d/m/Y') }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-filament::badge color="warning" size="lg" class="w-full justify-center">
                                <x-filament::icon  class="h-4 w-4 mr-2" />
                                No se encontraron asignaciones para los parámetros seleccionados
                            </x-filament::badge>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <x-filament::icon 
                                class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                            <h3 class="mt-2 text-sm font-medium text-gray-950 dark:text-white">Sin datos seleccionados
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Complete el formulario de la izquierda para ver la vista previa del reporte
                            </p>
                        </div>
                    @endif
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
