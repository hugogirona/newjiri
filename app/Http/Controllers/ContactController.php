<?php

namespace App\Http\Controllers;

use App\Concerns\HandleImages;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HandleImages;

    public function index()
    {
        $contacts = auth()->user()->contacts()
            ->orderBy('last_name')
            ->paginate(8);

        return view('contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);

        // Charger uniquement les jiris
        $contact->load([
            'jiris' => function ($query) {
                $query->orderBy('starts_at', 'desc');
            },
            'implementations.assignment.project',
            'implementations.assignment.jiri'
        ]);


        return view('contacts.show', compact('contact'));
    }

    public function create()
    {
        $this->authorize('create', Contact::class);

        // Récupérer tous les jurys de l'utilisateur avec leurs projets
        $jiris = auth()->user()->jiris()
            ->with('projects')
            ->orderBy('starts_at', 'desc')
            ->get();

        return view('contacts.create', compact('jiris'));
    }

    public function store(StoreContactRequest $request)
    {
        $this->authorize('create', Contact::class);

        $validated = $request->validated();

        // Gérer l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->generateAvatarImages($request->file('avatar'));
        }

        $validated['user_id'] = auth()->id();

        $contact = Contact::create($validated);

        // Attacher les participations aux jurys...
        if (!empty($validated['jiri_ids'])) {
            foreach ($validated['jiri_ids'] as $jiriId) {
                if (isset($validated['jiri_roles'][$jiriId])) {
                    $contact->jiris()->attach($jiriId, [
                        'role' => $validated['jiri_roles'][$jiriId]
                    ]);

                    if ($validated['jiri_roles'][$jiriId] === 'evaluated' &&
                        isset($validated['jiri_projects'][$jiriId])) {

                        foreach ($validated['jiri_projects'][$jiriId] as $projectId) {
                            $assignment = \App\Models\Assignment::where('jiri_id', $jiriId)
                                ->where('project_id', $projectId)
                                ->first();

                            if ($assignment) {
                                $contact->assignments()->attach($assignment->id);
                            }
                        }
                    }
                }
            }
        }

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Le contact a été créé avec succès.');
    }


    public function edit(Contact $contact)
    {
        $this->authorize('update', $contact);

        // Récupérer tous les jurys de l'utilisateur avec leurs projets
        $jiris = auth()->user()->jiris()
            ->with('projects')
            ->orderBy('starts_at', 'desc')
            ->get();

        // Charger les relations du contact
        $contact->load(['jiris', 'implementations.assignment.project']);

        return view('contacts.edit', compact('contact', 'jiris'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);

        $validated = $request->validated();


        if ($request->has('remove_avatar') && $contact->avatar) {
            Storage::disk('public')->delete($contact->avatar);
            $validated['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($contact->avatar) {
                Storage::disk('public')->delete($contact->avatar);
            }
            $validated['avatar'] = $this->generateAvatarImages($request->file('avatar'));
        }

        // Mettre à jour les informations du contact
        $contact->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'avatar' => $validated['avatar'] ?? $contact->avatar,
        ]);

        // Synchroniser les participations aux jurys
        if (isset($validated['jiri_ids'])) {
            $syncData = [];
            foreach ($validated['jiri_ids'] as $jiriId) {
                if (isset($validated['jiri_roles'][$jiriId])) {
                    $syncData[$jiriId] = ['role' => $validated['jiri_roles'][$jiriId]];
                }
            }
            $contact->jiris()->sync($syncData);

            // Gérer les implementations (projets pour les évalués)
            // D'abord, supprimer toutes les implementations existantes
            $contact->implementations()->delete();

            // Puis recréer les implementations pour les jurys dans lesquels le contact est évalué
            foreach ($validated['jiri_ids'] as $jiriId) {
                if (isset($validated['jiri_roles'][$jiriId]) &&
                    $validated['jiri_roles'][$jiriId] === 'evaluated' &&
                    isset($validated['jiri_projects'][$jiriId])) {

                    foreach ($validated['jiri_projects'][$jiriId] as $projectId) {
                        $assignment = \App\Models\Assignment::where('jiri_id', $jiriId)
                            ->where('project_id', $projectId)
                            ->first();

                        if ($assignment) {
                            $contact->assignments()->syncWithoutDetaching($assignment->id);
                        }
                    }
                }
            }
        } else {
            // Si aucun jury sélectionné, on détache tout
            $contact->jiris()->detach();
            $contact->implementations()->delete();
        }

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Le contact a été modifié avec succès.');
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Le contact a été supprimé avec succès.');
    }



}
