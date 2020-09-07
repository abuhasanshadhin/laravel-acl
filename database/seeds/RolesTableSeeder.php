<?php

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'editor', 'moderator'];

        $permissions = Permission::pluck('id')->toArray();

        foreach ($roles as $role) {

            $rolePermissions = [];

            for ($i = 0; $i < rand(1, count($permissions)); $i++) {

                shuffle($permissions);

                if (!in_array($permissions[$i], $rolePermissions)) {

                    $rolePermissions[] = $permissions[$i];
                }
            }

            $role = Role::create(['name' => $role]);
            $role->permissions()->attach($rolePermissions);
        }
    }
}
