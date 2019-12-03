<?php

use App\FlashcardDeck;
use Illuminate\Database\Seeder;

class FlashcardDeckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(FlashcardDeck::class, 100)->create();

    }
}
