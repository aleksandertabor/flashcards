<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersCounter = max((int) $this->command->ask('How many users would you like?', 20), 1);
        factory(App\User::class)->states('admin')->create();
        factory(App\User::class, $usersCounter)->create();
    }
}
