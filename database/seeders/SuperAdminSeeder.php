<?php
namespace Database\Seeders;

use App\Models\RoleStaff;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $email = 'superadmin@silicash.com';
        // Disable foreign key checks for MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

// Use Schema facade to disable foreign key checks for SQLite
        if (DB::getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();

        }

        $existingUser = User::where('email', $email)->withTrashed()->first();
        if ($existingUser) {
            $existingUser->forceDelete();
        }
        RoleStaff::truncate();
        Staff::truncate();
        // Re-enable foreign key checks
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::enableForeignKeyConstraints();
        }

        $roleId = getRoleId('super_admin');

        $adminData = [
            'email'    => $email,
            'password' => '1234567890',
            'gender'   => 'male',
            'role'     => 'staff',
        ];

        $user = User::create($adminData);

        $staffData = [
            'first_name' => 'Super',
            'last_name'  => 'admin',
        ];

        $staff = $user->staff()->create($staffData);

        $staff->roles()->attach($roleId);

    }
}
