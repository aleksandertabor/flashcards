<?php

use App\Deck;
use Illuminate\Database\Seeder;

class DeckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Deck::class, 100)->create()->each(function ($deck) {
            $cards = factory(App\Card::class, random_int(20, 50))->make();
            $deck->cards()->saveMany($cards);
        });

    }
}
