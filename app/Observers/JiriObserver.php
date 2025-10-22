<?php

namespace App\Observers;

use App\Events\JiriCreatedEvent;
use App\Models\Jiri;

class JiriObserver
{
    public function created(Jiri $jiri): void
    {
        // Déclencher l'événement automatiquement
        event(new JiriCreatedEvent($jiri));
    }

    /**
     * Handle the Jiri "updated" event.
     */
    public function updated(Jiri $jiri): void
    {
        // Si vous avez un événement JiriUpdatedEvent
        // event(new JiriUpdatedEvent($jiri));
    }

    /**
     * Handle the Jiri "deleted" event.
     */
    public function deleted(Jiri $jiri): void
    {
        // Nettoyer les relations si nécessaire
        $jiri->contacts()->detach();
        $jiri->projects()->detach();

        // Si vous avez un événement JiriDeletedEvent
        // event(new JiriDeletedEvent($jiri));
    }

    /**
     * Handle the Jiri "restored" event.
     */
    public function restored(Jiri $jiri): void
    {
        //
    }

    /**
     * Handle the Jiri "force deleted" event.
     */
    public function forceDeleted(Jiri $jiri): void
    {
        //
    }

}
