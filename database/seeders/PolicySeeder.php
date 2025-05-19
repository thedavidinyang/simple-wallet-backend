<?php
namespace Database\Seeders;

use App\Models\StaffRolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // Use Schema facade to disable foreign key checks for SQLite
        if (DB::getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();
        }
        StaffRolePermission::truncate();
        // Re-enable foreign key checks
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::enableForeignKeyConstraints();
        }
        
        $policies = [
            ['role_id' => 1, 'permission' => 'view_any'],
            ['role_id' => 1, 'permission' => 'view'],
            ['role_id' => 1, 'permission' => 'create'],
            ['role_id' => 1, 'permission' => 'update'],
            ['role_id' => 1, 'permission' => 'delete'],
        ];

        foreach ($policies as $policy) {
            StaffRolePermission::create($policy);
        }
    }
}
