<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearPdfCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:clear-cache {--vehicle= : Clear cache for specific vehicle ID} {--all : Clear all PDF cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear PDF cache for vehicle maintenance reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->clearAllPdfCache();
        } elseif ($vehicleId = $this->option('vehicle')) {
            $this->clearVehiclePdfCache($vehicleId);
        } else {
            $this->error('Please specify --all or --vehicle=ID option');
            return 1;
        }

        return 0;
    }

    /**
     * Clear all PDF cache
     */
    private function clearAllPdfCache()
    {
        $this->info('Clearing all PDF cache...');

        try {
            // Clear vehicle maintenance PDFs
            $pattern1 = '*vehicle_maintenance_pdf_*';
            $pattern2 = '*vehicle_maintenance_fast_pdf_*';
            $pattern3 = '*processed_image_*';

            $cleared = 0;

            // Usar diferentes métodos según el driver de caché
            if (config('cache.default') === 'redis') {
                $keys1 = Cache::getRedis()->keys($pattern1);
                $keys2 = Cache::getRedis()->keys($pattern2);
                $keys3 = Cache::getRedis()->keys($pattern3);

                $allKeys = array_merge($keys1 ?: [], $keys2 ?: [], $keys3 ?: []);

                if (!empty($allKeys)) {
                    Cache::getRedis()->del($allKeys);
                    $cleared = count($allKeys);
                }
            } else {
                // Para otros drivers, usar flush (menos eficiente pero funciona)
                Cache::flush();
                $cleared = 'all';
            }

            $this->info("Cleared {$cleared} PDF cache entries successfully!");
        } catch (\Exception $e) {
            $this->error('Error clearing cache: ' . $e->getMessage());
            $this->info('Trying alternative method...');
            Cache::flush();
            $this->info('Cache cleared using flush method.');
        }
    }

    /**
     * Clear PDF cache for specific vehicle
     */
    private function clearVehiclePdfCache($vehicleId)
    {
        $this->info("Clearing PDF cache for vehicle ID: {$vehicleId}");

        $pattern = "*vehicle_maintenance_pdf_{$vehicleId}*";
        $keys = Cache::getRedis()->keys($pattern);

        if (!empty($keys)) {
            Cache::getRedis()->del($keys);
            $this->info('Cleared ' . count($keys) . ' cache entries for vehicle ' . $vehicleId);
        } else {
            $this->info('No cache entries found for vehicle ' . $vehicleId);
        }
    }
}
