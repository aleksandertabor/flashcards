<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('passport:client --password -n');
    }
}
