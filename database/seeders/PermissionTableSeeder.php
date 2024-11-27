<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'membership-list',
            'membership-create',
            'membership-edit',
            'membership-delete',

            'pre-membership-list',
            'pre-membership-create',
            'pre-membership-edit',
            'pre-membership-delete',

            'service-connect-order-list',
            'service-connect-order-create',
            'service-connect-order-edit',
            'service-connect-order-delete',

            'lifeline-list',
            'lifeline-create',
            'lifeline-edit',
            'lifeline-delete',

            'material-requisition-form-list',
            'material-requisition-form-create',
            'material-requisition-form-edit',
            'material-requisition-form-delete',

            'electrician-list',
            'electrician-create',
            'electrician-edit',
            'electrician-delete',

            'cashier-transaction-list',
            'cashier-transaction-create',
            'cashier-transaction-edit',
            'cashier-transaction-delete',

            'change-meter-request-list',
            'change-meter-request-create',
            'change-meter-request-edit',
            'change-meter-request-delete',
         ];
      
         foreach ($permissions as $permission) {
              Permission::firstOrCreate(['name' => $permission]);
         }
    }
}
