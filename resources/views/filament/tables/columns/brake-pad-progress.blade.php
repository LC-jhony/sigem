@php
    $record = $getRecord();
    $average = $getState() ?? 0;
    $status = $record->brake_pad_status ?? '';
    $color = $record->brake_pad_status_color ?? 'gray';

    // Función para calcular color dinámico basado en porcentaje
    $getProgressColor = function($percentage) {
        if ($percentage >= 70) {
            // Verde: Excelente estado
            $intensity = min(100, ($percentage - 70) * 3.33); // 0-100 para 70-100%
            return "background: linear-gradient(90deg, rgb(34 197 94) 0%, rgb(22 163 74) 100%);";
        } elseif ($percentage >= 50) {
            // Verde a Amarillo: Transición de bueno a regular
            $ratio = ($percentage - 50) / 20; // 0-1 para 50-70%
            $green = 34 + (163 * $ratio);
            $rose = 234 - (200 * $ratio);
            return "background: linear-gradient(90deg, rgb({$rose} {$green} 94) 0%, rgb(" . ($rose-20) . " " . ($green-20) . " 74) 100%);";
        } elseif ($percentage >= 30) {
            // Amarillo a Naranja: Estado regular a preocupante
            $ratio = ($percentage - 30) / 20; // 0-1 para 30-50%
            $rose = 239 + (15 * (1-$ratio));
            $green = 68 + (166 * $ratio);
            return "background: linear-gradient(90deg, rgb({$rose} {$green} 68) 0%, rgb(" . ($rose-20) . " " . ($green-20) . " 48) 100%);";
        } else {
            // Rojo: Estado crítico
            $intensity = max(0, $percentage / 30); // 0-1 para 0-30%
            $rose = 220 + (19 * $intensity);
            return "background: linear-gradient(90deg, rgb({$rose} 38 38) 0%, rgb(185 28 28) 100%);";
        }
    };

    // Configuración de colores y estilos
    $config = match($color) {
        'success' => [
            'text' => 'text-success-700 dark:text-success-300',
            'badge' => 'bg-success-50 text-success-700 ring-success-600/20 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/30',
            'glow' => 'shadow-green-500/25',
            'icon' => '✓'
        ],
        'warning' => [
            'text' => 'text-warning-700 dark:text-warning-300',
            'badge' => 'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-400/10 dark:text-warning-400 dark:ring-warning-400/30',
            'glow' => 'shadow-yellow-500/25',
            'icon' => '⚠'
        ],
        'danger' => [
            'text' => 'text-danger-700 dark:text-danger-300',
            'badge' => 'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-400/10 dark:text-danger-400 dark:ring-danger-400/30',
            'glow' => 'shadow-rose-500/25',
            'icon' => '⚡'
        ],
        default => [
            'text' => 'text-gray-700 dark:text-gray-300',
            'badge' => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30',
            'glow' => 'shadow-gray-500/25',
            'icon' => '○'
        ]
    };

    $progressStyle = $getProgressColor($average);
@endphp

<div class="w-full fi-ta-text-item group px-3 py-4">
    <div class="flex items-center gap-4">
        {{-- Barra de progreso mejorada --}}
        <div class="flex-1 space-y-2">
            {{-- Contenedor principal --}}
            <div class="relative">
                {{-- Barra de fondo --}}
                <div class="h-3 w-full rounded-full bg-gray-200 dark:bg-gray-700 shadow-inner overflow-hidden">
                    {{-- Barra de progreso con color dinámico --}}
                    <div class="relative h-full rounded-full transition-all duration-700 ease-out {{ $config['glow'] }} shadow-sm"
                         style="width: {{ min(max($average, 0), 100) }}%; {{ $progressStyle }}">
                        {{-- Efecto de brillo animado --}}
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-60"></div>

                        {{-- Efecto de textura sutil --}}
                        <div class="absolute inset-0 rounded-full bg-gradient-to-t from-black/10 to-white/10"></div>

                        {{-- Indicador de porcentaje dentro de la barra --}}
                        @if($average >= 25)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xs font-medium text-white drop-shadow-lg">
                                    {{ number_format($average, 0) }}%
                                </span>
                            </div>
                        @endif

                        {{-- Punto de brillo en el extremo --}}
                        {{-- @if($average > 5)
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-1 bg-white/80 rounded-full shadow-sm"></div>
                        @endif --}}
                    </div>
                </div>

                {{-- Marcadores de referencia mejorados --}}
                <div class="absolute -top-1 h-5 w-full pointer-events-none">
                    {{-- Zona crítica (0-30%) - Fondo rojo sutil --}}
                    <div class="absolute left-0 top-1 h-3 w-[30%] bg-rose-500/10 dark:bg-rose-400/10 rounded-l-full"></div>

                    {{-- Zona regular (30-70%) - Fondo amarillo sutil --}}
                    <div class="absolute left-[30%] top-1 h-3 w-[40%] bg-yellow-500/10 dark:bg-yellow-400/10"></div>

                    {{-- Zona buena (70-100%) - Fondo verde sutil --}}
                    <div class="absolute left-[70%] top-1 h-3 w-[30%] bg-green-500/10 dark:bg-green-400/10 rounded-r-full"></div>

                    {{-- Marcadores de línea --}}
                    <div class="absolute left-[30%] top-0 h-full w-0.5 bg-rose-400/60 dark:bg-rose-500/60 rounded-full"></div>
                    <div class="absolute left-[70%] top-0 h-full w-0.5 bg-green-400/60 dark:bg-green-500/60 rounded-full"></div>
                </div>
            </div>       
        </div>


    </div>


</div>