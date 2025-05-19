<?php
namespace Database\Seeders;

use App\Models\StaffRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StaffRoleSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks for MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

// Use Schema facade to disable foreign key checks for SQLite
        if (DB::getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();
        }
        StaffRole::truncate();

        // Re-enable foreign key checks
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::enableForeignKeyConstraints();
        }
        $roles = [
            ['name' => 'super_admin', 'description' => 'Super Administrator with full access'],
            ['name' => 'admin', 'description' => 'Administrator with limited access'],
            ['name' => 'manager', 'description' => 'Manager with access to manage staff'],
            ['name' => 'staff', 'description' => 'Regular staff member'],
        ];

        foreach ($roles as $role) {
            StaffRole::create($role);
        }
    }
}
