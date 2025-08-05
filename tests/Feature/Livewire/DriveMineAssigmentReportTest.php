<?php

use App\Livewire\DriveMineAssigmentReport;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(DriveMineAssigmentReport::class)
        ->assertStatus(200);
});
