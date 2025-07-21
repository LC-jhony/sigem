<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Contracts\HasAffixActions;

class MaintenanceTable extends Component
{
    protected string $view = 'filament.components.maintenance-table';

    protected array $intervals = [];
    protected array $activities = [];
    protected Model $vehicle;
    public static function make(): static
    {
        return app(static::class);
    }

    public function intervals(array $intervals): static
    {
        $this->intervals = $intervals;
        return $this;
    }

    public function activities(array $activities): static
    {
        $this->activities = $activities;
        return $this;
    }

    public function vehicle(Model $vehicle): static
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function getIntervals(): array
    {
        return $this->intervals;
    }

    public function getActivities(): array
    {
        return $this->activities;
    }

    public function getVehicle(): Model
    {
        return $this->vehicle;
    }
}
