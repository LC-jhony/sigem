<?php

use App\Http\Controllers\MaintenanceHistoryController;
use App\Http\Controllers\MineAsigmentReportController;
use App\Http\Controllers\ValueMaintenanceVehicleController;
use App\Http\Controllers\VehicleDocumentPdf;
use App\Livewire\Mantenance\MantenaceTable;
use Illuminate\Support\Facades\Route;

Route::get('/mantenancetable', MantenaceTable::class)->name('mantenancetable');
Route::get('valuemantenacevehicle/{id}', ValueMaintenanceVehicleController::class)
    ->name('valuemantenacevehicle');
Route::get('maintenacehisrtory/{id}', MaintenanceHistoryController::class)
    ->name('maintenacehistory');

Route::get('vehicledocument/pdf', VehicleDocumentPdf::class)
    ->name('vehicledocument.pdf');

Route::get('mineassigmentreport/{id}', MineAsigmentReportController::class)
    ->name('mineassigmentreport');
// Route::get('/', function () {
//     return view('welcome');
// });
