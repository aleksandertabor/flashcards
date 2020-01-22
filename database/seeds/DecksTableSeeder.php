<?php

use App\Deck;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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

        $output = $this->command->getOutput();

        $decksCounter = max((int) $this->command->ask('How many decks would you like?', 100), 1);

        $bar = $output->createProgressBar($decksCounter);

        $bar->start();

        factory(Deck::class, $decksCounter)->create()->each(function ($deck) use ($users, $bar) {
            $deck->user_id = $users->random()->id;
            $cards = factory(App\Card::class, random_int(20, 50))->make();
            $deck->cards()->saveMany($cards);
            $deck->addMediaFromUrl(Storage::url('deck.png'))->toMediaCollection('main');
            $deck->save();
            $bar->advance();
        });

        $bar->finish();
    }
}
