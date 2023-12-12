<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $i                = 1;
        $permissions      = [];
        $permissionGroups = [
            'permission', 'role', 'user', 'task',
        ];

        foreach ($permissionGroups as $permissionGroup) {
            foreach (['access', 'create', 'edit', 'show', 'delete'] as $permission) {
                $permissions[] = [
                    'id'    => $i++,
                    'title' => $permissionGroup . '_management_' . $permission,
                ];
            }
        }

        Permission::insert($permissions);

    }
}
