@php
    $record = $getRecord();
    $average = $getState() ?? 0;
    $status = $record->brake_pad_status ?? '';
    $color = $record->brake_pad_status_color ?? 'gray';

    // Función para calcular color dinámico basado en porcentaje
    $getProgressColor = function($percentage) {
        if ($percentage >= 70) {
            $intensity = min(100, ($percentage - 70) * 3.33);
            return "background: linear-gradient(90deg, #10b981 0%, #059669 100%);";
        } elseif ($percentage >= 50) {
            $ratio = ($percentage - 50) / 20;
            $green = 16 + (150 * $ratio);
            $yellow = 234 - (150 * $ratio);
            return "background: linear-gradient(90deg, rgb($yellow $green 18) 0%, #ca8a04 100%);";
        } elseif ($percentage >= 30) {
            $ratio = ($percentage - 30) / 20;
            $orange = 239 + (16 * (1 - $ratio));
            $amber = 68 + (98 * $ratio);
            return "background: linear-gradient(90deg, rgb($orange $amber 16) 0%, #c2410c 100%);";
        } else {
            $intensity = max(0, $percentage / 30);
            $red = 220 + (19 * $intensity);
            return "background: linear-gradient(90deg, rgb($red 20 20) 0%, #b91c1c 100%);";
        }
    };

    $config = match($color) {
        'success' => [
            'text' => 'text-emerald-700 dark:text-emerald-400',
            'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/30',
            'glow' => 'shadow-emerald-500/15',
            'icon' => '✓'
        ],
        'warning' => [
            'text' => 'text-amber-700 dark:text-amber-400',
            'badge' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-400 dark:ring-amber-400/30',
            'glow' => 'shadow-amber-500/15',
            'icon' => '⚠'
        ],
        'danger' => [
            'text' => 'text-rose-700 dark:text-rose-400',
            'badge' => 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-400/10 dark:text-rose-400 dark:ring-rose-400/30',
            'glow' => 'shadow-rose-500/15',
            'icon' => '⚡'
        ],
        default => [
            'text' => 'text-gray-700 dark:text-gray-300',
            'badge' => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30',
            'glow' => 'shadow-gray-500/15',
            'icon' => '○'
        ]
    };

    $progressStyle = $getProgressColor($average);
@endphp

<div class="w-full fi-ta-text-item group px-3 py-4">
    <div class="flex items-center gap-4">
        {{-- Barra de progreso mejorada con diseño moderno --}}
        <div class="flex-1 space-y-2">
            <div class="relative">
                {{-- Barra de fondo con textura sutil --}}
                <div class="h-3 w-full rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    {{-- Barra de progreso con gradiente, brillo y profundidad --}}
                    <div 
                        class="relative h-full rounded-full transition-all duration-1000 ease-in-out {{ $config['glow'] }} shadow-sm"
                        style="width: {{ min(max($average, 0), 100) }}%; {{ $progressStyle }}">
                        
                        {{-- Brillo interno sutil (efecto de volumen) --}}
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-white/40 via-transparent to-transparent opacity-70 pointer-events-none"></div>

                        {{-- Textura de profundidad (más sutil) --}}
                        <div class="absolute inset-0 rounded-full bg-gradient-to-t from-transparent to-black/5"></div>

                        {{-- Porcentaje visible solo si hay espacio --}}
                        @if($average >= 20)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xs font-semibold text-white drop-shadow-sm tracking-tight">
                                    {{ number_format($average, 0) }}%
                                </span>
                            </div>
                        @endif

                        {{-- Punto de resaltado en el extremo (opcional) --}}
                        @if($average > 10)
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-white/90 rounded-full shadow-inner"></div>
                        @endif
                    </div>
                </div>

                {{-- Marcadores de referencia con mejor jerarquía --}}
                <div class="absolute -top-1.5 h-6 w-full pointer-events-none">
                    {{-- Zonas de color semitransparente --}}
                    <div class="absolute left-0 top-2 h-1.5 w-[30%] bg-gradient-to-r from-rose-500/20 to-rose-500/10 dark:from-rose-400/20 dark:to-rose-400/10 rounded-l-full"></div>
                    <div class="absolute left-[30%] top-2 h-1.5 w-[40%] bg-gradient-to-r from-amber-400/20 to-yellow-400/10 dark:from-amber-400/20 dark:to-yellow-400/10"></div>
                    <div class="absolute left-[70%] top-2 h-1.5 w-[30%] bg-gradient-to-r from-emerald-400/20 to-green-400/10 dark:from-emerald-400/20 dark:to-green-400/10 rounded-r-full"></div>

                    {{-- Líneas divisorias más delgadas y sutiles --}}
                    <div class="absolute left-[30%] top-1.5 h-2.5 w-px bg-rose-400/50 dark:bg-rose-500/50"></div>
                    <div class="absolute left-[70%] top-1.5 h-2.5 w-px bg-emerald-400/50 dark:bg-emerald-500/50"></div>
                </div>
            </div>
        </div>
    </div>
</div>