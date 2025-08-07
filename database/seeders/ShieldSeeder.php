<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_cargo","view_any_cargo","create_cargo","update_cargo","restore_cargo","restore_any_cargo","replicate_cargo","reorder_cargo","delete_cargo","delete_any_cargo","force_delete_cargo","force_delete_any_cargo","view_driver","view_any_driver","create_driver","update_driver","restore_driver","restore_any_driver","replicate_driver","reorder_driver","delete_driver","delete_any_driver","force_delete_driver","force_delete_any_driver","view_driver::license","view_any_driver::license","create_driver::license","update_driver::license","restore_driver::license","restore_any_driver::license","replicate_driver::license","reorder_driver::license","delete_driver::license","delete_any_driver::license","force_delete_driver::license","force_delete_any_driver::license","view_driver::mine::assigment::mine","view_any_driver::mine::assigment::mine","create_driver::mine::assigment::mine","update_driver::mine::assigment::mine","restore_driver::mine::assigment::mine","restore_any_driver::mine::assigment::mine","replicate_driver::mine::assigment::mine","reorder_driver::mine::assigment::mine","delete_driver::mine::assigment::mine","delete_any_driver::mine::assigment::mine","force_delete_driver::mine::assigment::mine","force_delete_any_driver::mine::assigment::mine","view_maintenance","view_any_maintenance","create_maintenance","update_maintenance","restore_maintenance","restore_any_maintenance","replicate_maintenance","reorder_maintenance","delete_maintenance","delete_any_maintenance","force_delete_maintenance","force_delete_any_maintenance","view_maintenance::item","view_any_maintenance::item","create_maintenance::item","update_maintenance::item","restore_maintenance::item","restore_any_maintenance::item","replicate_maintenance::item","reorder_maintenance::item","delete_maintenance::item","delete_any_maintenance::item","force_delete_maintenance::item","force_delete_any_maintenance::item","view_mine","view_any_mine","create_mine","update_mine","restore_mine","restore_any_mine","replicate_mine","reorder_mine","delete_mine","delete_any_mine","force_delete_mine","force_delete_any_mine","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","view_vehicle","view_any_vehicle","create_vehicle","update_vehicle","restore_vehicle","restore_any_vehicle","replicate_vehicle","reorder_vehicle","delete_vehicle","delete_any_vehicle","force_delete_vehicle","force_delete_any_vehicle","page_MineAssigmentReport","widget_InformationSistemWidget","widget_LatestVehiclesTable","widget_VehicleWindget","widget_LatestMaintenanceWidget","widget_MaintenanceChartWidget"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
