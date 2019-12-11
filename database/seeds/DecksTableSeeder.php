<?php

use App\Deck;
use Illuminate\Database\Seeder;

class DecksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = App\User::all();
        factory(Deck::class, 100)->create()->each(function ($deck) use ($users) {
            $deck->user_id = $users->random()->id;
            $cards = factory(App\Card::class, random_int(20, 50))->make();
            $deck->cards()->saveMany($cards);
            $deck->save();
        });

    }
}
