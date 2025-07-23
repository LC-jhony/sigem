<?php

use App\Http\Controllers\MaintenanceHistoryController;
use App\Http\Controllers\ValueMaintenanceVehicleController;
use App\Livewire\Mantenance\MantenaceTable;
use Illuminate\Support\Facades\Route;


Route::get('/mantenancetable', MantenaceTable::class)->name('mantenancetable');
Route::get('valuemantenacevehicle/{id}', ValueMaintenanceVehicleController::class)
    ->name('valuemantenacevehicle');
Route::get('maintenacehisrtory/{id}', MaintenanceHistoryController::class)
    ->name('maintenacehistory');

// Route::get('/', function () {
//     return view('welcome');
// });
