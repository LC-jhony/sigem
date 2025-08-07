<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ValueMaintenanceVehicleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        try {
            // Optimización 1: Caché inteligente
            $bypassCache = $request->has('no_cache') || config('app.debug', false);
            $cacheKey = "vehicle_maintenance_pdf_{$id}";

            if (!$bypassCache) {
                $cachedPdf = Cache::get($cacheKey);
                if ($cachedPdf) {
                    return response($cachedPdf)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'inline; filename="vehicle_maintenance_' . $id . '.pdf"');
                }
            }

            // Optimización 2: Query optimizada con selección específica de campos
            $record = Vehicle::select('id', 'placa', 'marca', 'unidad', 'property_card')
                ->with([
                    'maintenances' => function ($query) {
                        $query->select(
                            'id',
                            'vehicle_id',
                            'maintenance_item_id',
                            'mileage',
                            'Price_material',
                            'workforce',
                            'maintenance_cost',
                            'photo',
                            'file'
                        )
                            ->orderBy('mileage', 'asc')
                            ->limit(100); // Limitar para evitar PDFs gigantes
                    },
                    'maintenances.maintenanceItem:id,name'
                ])
                ->findOrFail($id);

            // Optimización 3: Pre-procesar imágenes para evitar operaciones durante renderizado
            $processedMaintenances = $record->maintenances->map(function ($maintenance) {
                $maintenance->processed_photo = null;
                $maintenance->processed_file = null;

                if ($maintenance->photo) {
                    $photoPath = storage_path('app/public/' . $maintenance->photo);
                    if (file_exists($photoPath) && is_readable($photoPath)) {
                        try {
                            $photoMime = mime_content_type($photoPath);
                            if (in_array($photoMime, ['image/jpeg', 'image/png', 'image/gif'])) {
                                $maintenance->processed_photo = [
                                    'data' => base64_encode(file_get_contents($photoPath)),
                                    'mime' => $photoMime
                                ];
                            }
                        } catch (\Exception $e) {
                            Log::warning("Error processing photo: " . $e->getMessage());
                        }
                    }
                }

                if ($maintenance->file) {
                    $filePath = storage_path('app/public/' . $maintenance->file);
                    if (file_exists($filePath) && is_readable($filePath)) {
                        try {
                            $fileMime = mime_content_type($filePath);
                            if (in_array($fileMime, ['image/jpeg', 'image/png', 'image/gif'])) {
                                $maintenance->processed_file = [
                                    'data' => base64_encode(file_get_contents($filePath)),
                                    'mime' => $fileMime
                                ];
                            }
                        } catch (\Exception $e) {
                            Log::warning("Error processing file: " . $e->getMessage());
                        }
                    }
                }

                return $maintenance;
            });

            $record->setRelation('maintenances', $processedMaintenances);

            // Optimización 4: Configuración PDF optimizada
            $pdf = Pdf::loadView('pdf.value-mantenace-vehicle-optimized', [
                'record' => $record,
            ])
                ->setOptions([
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => false,
                    'isFontSubsettingEnabled' => false,
                    'debugKeepTemp' => false,
                ])
                ->setPaper('a4', 'portrait')
                ->setWarnings(false);

            $pdfOutput = $pdf->output();

            // Optimización 5: Cachear el resultado por 2 horas
            if (!$bypassCache) {
                Cache::put($cacheKey, $pdfOutput, 7200);
            }

            return response($pdfOutput)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="vehicle_maintenance_' . $id . '.pdf"')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            Log::error("Error generating PDF for vehicle {$id}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());

            return response()->json([
                'error' => 'Error al generar el PDF',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método de prueba simple para verificar que el PDF funcione
     */
    public function testPdf($id)
    {
        try {
            $record = Vehicle::select('id', 'placa', 'marca', 'unidad', 'property_card')
                ->findOrFail($id);

            // Crear datos de prueba simples
            $record->maintenances = collect([]);

            $html = "
            <html>
            <head>
                <title>Test PDF</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h2>Test PDF - Vehículo {$record->placa}</h2>
                </div>
                <p><strong>ID:</strong> {$record->id}</p>
                <p><strong>Placa:</strong> {$record->placa}</p>
                <p><strong>Marca:</strong> {$record->marca}</p>
                <p><strong>Unidad:</strong> {$record->unidad}</p>
                <p><strong>Tarjeta:</strong> {$record->property_card}</p>
                <p>PDF generado exitosamente en: " . now() . "</p>
            </body>
            </html>";

            $pdf = Pdf::loadHTML($html);
            return $pdf->stream("test_vehicle_{$id}.pdf");
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en test PDF',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Genera PDF ultra-rápido sin imágenes (para máxima velocidad)
     */
    public function fastPdf(Request $request, $id)
    {
        try {
            $cacheKey = "vehicle_maintenance_fast_pdf_{$id}";

            // Caché más agresivo para versión rápida
            if (!$request->has('no_cache')) {
                $cachedPdf = Cache::get($cacheKey);
                if ($cachedPdf) {
                    return response($cachedPdf)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'inline; filename="vehicle_maintenance_fast_' . $id . '.pdf"');
                }
            }

            // Query ultra-optimizada sin imágenes
            $record = Vehicle::select('id', 'placa', 'marca', 'unidad', 'property_card')
                ->with([
                    'maintenances' => function ($query) {
                        $query->select(
                            'vehicle_id',
                            'maintenance_item_id',
                            'mileage',
                            'Price_material',
                            'workforce',
                            'maintenance_cost'
                        )
                            ->orderBy('mileage', 'asc')
                            ->limit(50); // Límite más estricto
                    },
                    'maintenances.maintenanceItem:id,name'
                ])
                ->findOrFail($id);

            // PDF minimalista sin procesamiento de imágenes
            $pdf = Pdf::loadView('pdf.value-mantenace-vehicle-fast', [
                'record' => $record,
            ])
                ->setOptions([
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => false,
                    'isFontSubsettingEnabled' => false,
                ])
                ->setPaper('a4', 'portrait')
                ->setWarnings(false);

            $pdfOutput = $pdf->output();

            // Caché por 4 horas para versión rápida
            Cache::put($cacheKey, $pdfOutput, 14400);

            return response($pdfOutput)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="vehicle_maintenance_fast_' . $id . '.pdf"')
                ->header('Cache-Control', 'public, max-age=7200');
        } catch (\Exception $e) {
            Log::error("Error generating fast PDF for vehicle {$id}: " . $e->getMessage());

            return response()->json([
                'error' => 'Error al generar el PDF rápido',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Procesa y optimiza imágenes para el PDF
     */
    private function processImage(string $imagePath): ?array
    {
        try {
            // Caché de imágenes procesadas
            $imageHash = md5($imagePath . filemtime($imagePath));
            $cacheKey = "processed_image_{$imageHash}";

            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }

            $mime = mime_content_type($imagePath);

            // Solo procesar imágenes válidas
            if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                return null;
            }

            // Leer y optimizar imagen
            $imageData = file_get_contents($imagePath);

            // Comprimir imagen si es muy grande
            if (strlen($imageData) > 1048576) { // 1MB
                $imageData = $this->compressImage($imagePath, $mime);
            }

            $result = [
                'data' => base64_encode($imageData),
                'mime' => $mime
            ];

            // Cachear imagen procesada por 24 horas
            Cache::put($cacheKey, $result, 86400);

            return $result;
        } catch (\Exception $e) {
            // Log error pero continúa sin la imagen
            Log::warning("Error processing image {$imagePath}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Comprime imágenes grandes
     */
    private function compressImage(string $imagePath, string $mime): string
    {
        try {
            $image = null;

            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($imagePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($imagePath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($imagePath);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($imagePath);
                    break;
            }

            if (!$image) {
                return file_get_contents($imagePath);
            }

            // Redimensionar si es muy grande
            $width = imagesx($image);
            $height = imagesy($image);

            if ($width > 800 || $height > 600) {
                $ratio = min(800 / $width, 600 / $height);
                $newWidth = intval($width * $ratio);
                $newHeight = intval($height * $ratio);

                $resized = imagecreatetruecolor($newWidth, $newHeight);

                // Preservar transparencia para PNG
                if ($mime === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                }

                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            // Generar imagen comprimida
            ob_start();
            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($image, null, 75); // Calidad 75%
                    break;
                case 'image/png':
                    imagepng($image, null, 6); // Compresión nivel 6
                    break;
                case 'image/gif':
                    imagegif($image);
                    break;
                case 'image/webp':
                    imagewebp($image, null, 75);
                    break;
            }
            $compressedData = ob_get_contents();
            ob_end_clean();

            imagedestroy($image);

            return $compressedData ?: file_get_contents($imagePath);
        } catch (\Exception $e) {
            return file_get_contents($imagePath);
        }
    }
}
