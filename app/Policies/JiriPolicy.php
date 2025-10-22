<?php

namespace App\Policies;

use App\Models\Jiri;
use App\Models\User;

class JiriPolicy
{
    /**
     * Determine whether the user can view any Jiris (index).
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur connecté peut voir ses propres Jiris (filtrés dans le controller)
        return true;
    }

    /**
     * Determine whether the user can view the given Jiri.
     */
    public function view(User $user, Jiri $jiri): bool
    {
        // L'utilisateur ne peut voir que ses propres Jiris
        return $user->id === $jiri->user_id;
    }

    /**
     * Determine whether the user can create Jiris.
     */
    public function create(User $user): bool
    {
        // Tout utilisateur connecté peut créer un Jiri
        return true;
    }

    /**
     * Determine whether the user can update the given Jiri.
     */
    public function update(User $user, Jiri $jiri): bool
    {
        // Uniquement le propriétaire du Jiri
        return $user->id === $jiri->user_id;
    }

    /**
     * Determine whether the user can delete the given Jiri.
     */
    public function delete(User $user, Jiri $jiri): bool
    {
        // Uniquement le propriétaire du Jiri
        return $user->id === $jiri->user_id;
    }

    /**
     * Determine whether the user can restore the given Jiri.
     */
    public function restore(User $user, Jiri $jiri): bool
    {
        // Optionnel : seul le propriétaire
        return $user->id === $jiri->user_id;
    }

    /**
     * Determine whether the user can permanently delete the given Jiri.
     */
    public function forceDelete(User $user, Jiri $jiri): bool
    {
        // Optionnel : seul le propriétaire
        return $user->id === $jiri->user_id;
    }
}
