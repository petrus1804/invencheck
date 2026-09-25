<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::updateOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $staff = Role::updateOrCreate(['slug' => 'staff'], ['name' => 'Staff']);
        $user = Role::updateOrCreate(['slug' => 'user'], ['name' => 'User']);

        // $admin->permissions()->sync(Permission::pluck('id'));
        $admin->permissions()->sync(
            Permission::where('slug', '!=', 'restock_items')->pluck('id')
        );

        $staff->permissions()->sync(
            Permission::whereIn('slug', [
                'view_dashboard', 'view_stock', 'manage_stock',
                'view_categories', 'view_warehouses',
                'view_reports', 'approve_requests', 'restock_items',
            ])->pluck('id')
        );

        $user->permissions()->sync(
            Permission::whereIn('slug', ['request_items'])->pluck('id')
        );
    }
}