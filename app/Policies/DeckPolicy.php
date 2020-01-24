<?php

namespace App\Policies;

use App\Deck;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeckPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any decks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the deck.
     *
     * @param  \App\User  $user
     * @param  \App\Deck  $deck
     * @return mixed
     */
    public function view(?User $user, Deck $deck)
    {
        if ($deck->visibility === 'public' || $deck->visibility === 'unlisted') {
            return true;
        } else {
            return $user->id === $deck->user_id;
        }
    }

    /**
     * Determine whether the user can create decks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the deck.
     *
     * @param  \App\User  $user
     * @param  \App\Deck  $deck
     * @return mixed
     */
    public function update(User $user, Deck $deck)
    {
        return $user->id === $deck->user_id;
    }

    /**
     * Determine whether the user can delete the deck.
     *
     * @param  \App\User  $user
     * @param  \App\Deck  $deck
     * @return mixed
     */
    public function delete(User $user, Deck $deck)
    {
        return $user->id === $deck->user_id;
    }

    /**
     * Determine whether the user can restore the deck.
     *
     * @param  \App\User  $user
     * @param  \App\Deck  $deck
     * @return mixed
     */
    public function restore(User $user, Deck $deck)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the deck.
     *
     * @param  \App\User  $user
     * @param  \App\Deck  $deck
     * @return mixed
     */
    public function forceDelete(User $user, Deck $deck)
    {
        //
    }
}
