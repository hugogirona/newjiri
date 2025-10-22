<?php

namespace App\Http\Controllers;

use App\Events\JiriCreatedEvent;
use App\Http\Requests\StoreJiriRequest;
use App\Mail\JiriCreatedMail;
use App\Models\Jiri;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JiriController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index()
    {
        $this->authorize('viewAny', Jiri::class);

        $jiris = Auth::user()->jiris()
            ->latest('starts_at')
            ->paginate(10);

        return view('jiris.index', compact('jiris'));
    }
    public function show(Jiri $jiri)
    {
        $this->authorize('view', $jiri);

        $jiri->load(['evaluators', 'evaluated', 'projects', 'assignments']);

        return view('jiris.show', compact('jiri'));
    }

    public function create()
    {
        $this->authorize('create', Jiri::class);

        $contacts = Auth::user()->contacts()->orderBy('last_name')->get();
        $projects = Auth::user()->projects()->orderBy('title')->get();

        return view('jiris.create', compact('contacts', 'projects'));
    }

    public function store(StoreJiriRequest $request, Jiri $jiri)
    {
        $this->authorize('create', $jiri);

        $validated = $request->validated();

        // Créer le jury
        $jiri = auth()->user()->jiris()->create($validated);

        // Attacher les contacts avec leurs rôles
//        if($request->has('contacts'))

        if (!empty($validated['contact_ids'])) {
            $userContactIds = auth()->user()->contacts()
                ->whereIn('id', $validated['contact_ids'])
                ->pluck('id');

            $syncData = [];
            foreach ($userContactIds as $contactId) {
                if (isset($validated['contact_roles'][$contactId])) {
                    $syncData[$contactId] = ['role' => $validated['contact_roles'][$contactId]];
                }
            }

            $jiri->contacts()->sync($syncData);
        }

        // Attacher les projets existants
        if (!empty($validated['projects'])) {
            $userProjectIds = auth()->user()->projects()
                ->whereIn('id', $validated['projects'])
                ->pluck('id');

            $jiri->projects()->sync($userProjectIds);
        }

        event(new JiriCreatedEvent($jiri));

        return redirect()->route('jiris.show', $jiri)
            ->with('success', 'Le jury a été créé avec succès.');
    }

    public function edit(Jiri $jiri)
    {
        // Vérifier que le jury appartient à l'utilisateur
      $this->authorize('update', $jiri);


        // Récupérer tous les contacts de l'utilisateur
        $contacts = auth()->user()->contacts()
            ->orderBy('last_name')
            ->get();

        // Récupérer tous les projets de l'utilisateur
        $projects = auth()->user()->projects()
            ->orderBy('title')
            ->get();

        // Charger les relations du jury pour l'affichage
        $jiri->load(['contacts', 'projects']);

        return view('jiris.edit', compact('jiri', 'contacts', 'projects'));
    }

    public function update(StoreJiriRequest $request, Jiri $jiri)
    {

        $this->authorize('update', $jiri);


        $validated = $request->validated();

        // Mettre à jour les informations du jury
        $jiri->update($validated);

        // Synchroniser les contacts avec leurs rôles
        if (isset($validated['contact_ids'])) {
            $userContactIds = auth()->user()->contacts()
                ->whereIn('id', $validated['contact_ids'])
                ->pluck('id');

            $syncData = [];
            foreach ($userContactIds as $contactId) {
                if (isset($validated['contact_roles'][$contactId])) {
                    $syncData[$contactId] = ['role' => $validated['contact_roles'][$contactId]];
                }
            }

            $jiri->contacts()->sync($syncData);
        } else {
            // Si aucun contact sélectionné, détacher tous
            $jiri->contacts()->detach();
        }

        // Synchroniser les projets
        if (isset($validated['projects'])) {
            $userProjectIds = auth()->user()->projects()
                ->whereIn('id', $validated['projects'])
                ->pluck('id');

            $jiri->projects()->sync($userProjectIds);
        } else {
            // Si aucun projet sélectionné, détacher tous
            $jiri->projects()->detach();
        }

        return redirect()->route('jiris.show', $jiri)
            ->with('success', 'Le jury a été modifié avec succès.');
    }

    public function destroy(Jiri $jiri)
    {

        $this->authorize('delete', $jiri);

        $jiri->delete();

        return redirect()->route('jiris.index')
            ->with('success', 'Le jury a été supprimé avec succès.');
    }
}
