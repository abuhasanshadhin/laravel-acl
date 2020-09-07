<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'admin' => ['admin-list', 'admin-create', 'admin-edit', 'admin-delete'],
            'role' => ['role-list', 'role-create', 'role-edit', 'role-delete'],
            'category' => ['category-list', 'category-create', 'category-edit', 'category-delete'],
        ];

        foreach ($permissions as $batch => $names) {
            foreach ($names as $name) {
                Permission::create([
                    'name' => $name,
                    'batch' => $batch
                ]);
            }
        }
    }
}
