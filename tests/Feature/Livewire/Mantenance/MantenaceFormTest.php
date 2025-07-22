<?php

use App\Livewire\Mantenance\MantenaceForm;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(MantenaceForm::class)
        ->assertStatus(200);
});
