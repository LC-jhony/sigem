<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ValueMaintenanceVehicleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        // Generate cache key based on vehicle ID and last update
        $vehicle = Vehicle::findOrFail($id);
        $cacheKey = "maintenance_pdf_{$id}_" . $vehicle->updated_at->timestamp;
        
        // Check if we have a cached PDF
        if (Cache::has($cacheKey)) {
            return response()->streamDownload(function () use ($cacheKey) {
                echo Cache::get($cacheKey);
            }, $vehicle->placa . '_maintenance.pdf');
        }

        // Load vehicle with optimized eager loading
        $record = Vehicle::with([
            'maintenances' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'maintenances.maintenanceItem'
        ])->findOrFail($id);

        // Optimize images before rendering
        $this->optimizeImages($record->maintenances);

        $pdf = Pdf::loadView('pdf.value-maintenance', [
            'records' => $record->maintenances,
            'vehicle' => $record,
        ]);

        // Set PDF options for better performance
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
        ]);

        $pdfContent = $pdf->output();

        // Cache the PDF for 1 hour
        Cache::put($cacheKey, $pdfContent, now()->addHour());

        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, $vehicle->placa . '_maintenance.pdf');
    }

    /**
     * Optimize images for PDF generation
     */
    private function optimizeImages($maintenances)
    {
        foreach ($maintenances as $maintenance) {
            // Pre-optimize images if they exist
            if ($maintenance->photo && Storage::disk('public')->exists($maintenance->photo)) {
                $this->optimizeImage($maintenance->photo);
            }
            
            if ($maintenance->file && Storage::disk('public')->exists($maintenance->file)) {
                $this->optimizeImage($maintenance->file);
            }
        }
    }

    /**
     * Optimize a single image for better PDF performance
     */
    private function optimizeImage($path)
    {
        $fullPath = Storage::disk('public')->path($path);
        
        // Check if image is already optimized (you can add a flag in database)
        if (file_exists($fullPath)) {
            // For now, we'll just ensure the file is readable
            // In a production environment, you might want to resize/compress images
            return true;
        }
        
        return false;
    }
}
