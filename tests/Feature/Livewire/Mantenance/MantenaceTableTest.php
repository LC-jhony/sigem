<?php

use App\Livewire\Mantenance\MantenaceTable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(MantenaceTable::class)
        ->assertStatus(200);
});
