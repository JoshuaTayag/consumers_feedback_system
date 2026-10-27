<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PermissionTableSeeder::class);
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Joshua Tayag',
            'email' => 'joshua_tayag@leyeco-v.com.ph',
            'password' => bcrypt('PASSWORD'), // Set a default password for the test user
        ]);

        // Create a new role
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        // Assign all permissions to the role
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);

        echo "Admin role created and all permissions assigned.\n";

    }
}
