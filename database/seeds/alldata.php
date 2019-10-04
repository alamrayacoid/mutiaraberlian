<?php

use Illuminate\Database\Seeder;

class alldata extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = base_path('database\seeds\bisnis_mutiara.sql');
        DB::unprepared(file_get_contents($sql));
        $this->command->info('alldata Seeded!');
    }
}
