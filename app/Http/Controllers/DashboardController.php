<?php

namespace App\Http\Controllers;

use App\Models\Implementation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistiques générales
        $stats = [
            'jiris_count' => $user->jiris()->count(),
            'upcoming_jiris_count' => $user->jiris()->where('starts_at', '>', now())->count(),
            'contacts_count' => $user->contacts()->count(),
            'evaluators_count' => $user->jiris()
                ->join('attendances', 'jiris.id', '=', 'attendances.jiri_id')
                ->where('attendances.role', 'evaluator')
                ->distinct('attendances.contact_id')
                ->count('attendances.contact_id'),
            'evaluated_count' => $user->jiris()
                ->join('attendances', 'jiris.id', '=', 'attendances.jiri_id')
                ->where('attendances.role', 'evaluated')
                ->distinct('attendances.contact_id')
                ->count('attendances.contact_id'),
            'projects_count' => $user->projects()->count(),
            'implementations_count' => Implementation::whereHas('assignment.jiri', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'last_jiri' => $user->jiris()->latest('starts_at')->first(),
        ];

        // Jurys à venir (3 prochains)
        $upcomingJiris = $user->jiris()
            ->where('starts_at', '>', now())
            ->withCount(['contacts', 'projects'])
            ->orderBy('starts_at')
            ->take(3)
            ->get();

        // Contacts récents (5 derniers)
        $recentContacts = $user->contacts()
            ->latest()
            ->take(5)
            ->get();

        // Projets récents (5 derniers)
        $recentProjects = $user->projects()
            ->withCount('jiris')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'upcomingJiris', 'recentContacts', 'recentProjects'));
    }

}
