<?php

use App\Livewire\Mantenance\MantenaceTable;
use Illuminate\Support\Facades\Route;


Route::get('/mantenancetable', MantenaceTable::class)->name('mantenancetable');
// Route::get('/', function () {
//     return view('welcome');
// });
