<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Implementation;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $projects = auth()->user()->projects()
            ->withCount('jiris')
            ->orderBy('title')
            ->paginate(15);

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // Charger les relations
        $project->load([
            'jiris' => function ($query) {
                $query->orderBy('starts_at', 'desc');
            },
            'assignments.implementations.contact'
        ]);

        // Récupérer toutes les implementations de ce projet
        $implementations = Implementation::whereHas('assignment', function($query) use ($project) {
            $query->where('project_id', $project->id);
        })
            ->with(['contact', 'assignment.jiri'])
            ->get();

        $implementationsCount = $implementations->count();

        return view('projects.show', compact('project', 'implementations', 'implementationsCount'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        // Récupérer tous les jurys de l'utilisateur
        $jiris = auth()->user()->jiris()
            ->orderBy('starts_at', 'desc')
            ->get();

        return view('projects.create', compact('jiris'));
    }

    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validated();

        // Créer le projet
        $project = auth()->user()->projects()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        // Attacher les jurys (créer les assignments)
        if (!empty($validated['jiris'])) {
            $userJiris = auth()->user()->jiris()
                ->whereIn('id', $validated['jiris'])
                ->pluck('id');

            $project->jiris()->attach($userJiris);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Le projet a été créé avec succès.');
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        // Récupérer tous les jurys de l'utilisateur
        $jiris = auth()->user()->jiris()
            ->orderBy('starts_at', 'desc')
            ->get();

        // Charger les relations du projet
        $project->load(['jiris', 'assignments.implementations']);

        return view('projects.edit', compact('project', 'jiris'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validated();

        // Mettre à jour les informations du projet
        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        // Synchroniser les jurys
        if (isset($validated['jiris'])) {
            $userJiris = auth()->user()->jiris()
                ->whereIn('id', $validated['jiris'])
                ->pluck('id');

            $project->jiris()->sync($userJiris);
        } else {
            // Si aucun jury sélectionné, détacher tous
            $project->jiris()->detach();
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Le projet a été modifié avec succès.');
    }


    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Le projet a été supprimé avec succès.');
    }


}
