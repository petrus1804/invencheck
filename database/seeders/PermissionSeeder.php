<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Lihat Dashboard', 'slug' => 'view_dashboard', 'group' => 'Dashboard'],
            ['name' => 'Lihat Stok Barang', 'slug' => 'view_stock', 'group' => 'Stok Barang'],
            ['name' => 'Kelola Stok Barang', 'slug' => 'manage_stock', 'group' => 'Stok Barang'],
            ['name' => 'Catat Barang Masuk', 'slug' => 'restock_items', 'group' => 'Stok Barang'],
            ['name' => 'Lihat Kategori', 'slug' => 'view_categories', 'group' => 'Kategori'],
            ['name' => 'Kelola Kategori', 'slug' => 'manage_categories', 'group' => 'Kategori'],
            ['name' => 'Lihat Gudang', 'slug' => 'view_warehouses', 'group' => 'Gudang'],
            ['name' => 'Kelola Gudang', 'slug' => 'manage_warehouses', 'group' => 'Gudang'],
            ['name' => 'Lihat Laporan', 'slug' => 'view_reports', 'group' => 'Laporan'],
            ['name' => 'Setujui Permintaan', 'slug' => 'approve_requests', 'group' => 'Persetujuan'],
            ['name' => 'Kelola Pengguna', 'slug' => 'manage_users', 'group' => 'Pengaturan'],
            ['name' => 'Ajukan Pengambilan Barang', 'slug' => 'request_items', 'group' => 'Ambil Barang'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }
    }
}