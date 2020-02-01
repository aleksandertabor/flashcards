<?php

namespace App\Policies;

use App\Card;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any cards.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the card.
     *
     * @param  \App\User  $user
     * @param  \App\Card  $card
     * @return mixed
     */
    public function view(User $user, Card $card)
    {
        //
    }

    /**
     * Determine whether the user can create cards.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the card.
     *
     * @param  \App\User  $user
     * @param  \App\Card  $card
     * @return mixed
     */
    public function update(User $user, Card $card)
    {
        if (! $user->decks()->whereId($card->deck_id)->first()) {
            return false;
        }

        return $user->id === $user->decks()->whereId($card->deck_id)->first()->user_id;
    }

    /**
     * Determine whether the user can delete the card.
     *
     * @param  \App\User  $user
     * @param  \App\Card  $card
     * @return mixed
     */
    public function delete(User $user, Card $card)
    {
        if (! $user->decks()->whereId($card->deck_id)->first()) {
            return false;
        }

        return $user->id === $user->decks()->whereId($card->deck_id)->first()->user_id;
    }

    /**
     * Determine whether the user can restore the card.
     *
     * @param  \App\User  $user
     * @param  \App\Card  $card
     * @return mixed
     */
    public function restore(User $user, Card $card)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the card.
     *
     * @param  \App\User  $user
     * @param  \App\Card  $card
     * @return mixed
     */
    public function forceDelete(User $user, Card $card)
    {
        //
    }
}
