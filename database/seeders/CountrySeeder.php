<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
        DB::table('countries')->truncate();
        // Re-enable foreign key checks
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::enableForeignKeyConstraints();
        }

        $path = public_path('sql/countries.sql');
        $sql  = file_get_contents($path);
        DB::unprepared($sql);

    }
}
