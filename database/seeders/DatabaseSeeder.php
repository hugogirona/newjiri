<?php

namespace Database\Seeders;

use App\Enums\ContactRoles;
use App\Models\Assignment;
use App\Models\Contact;
use App\Models\Jiri;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user_a = User::factory()->create([
            'first_name' => 'Hugo',
            'last_name' => 'Girona',
            'email' => 'gironahugo@gmail.com',
            'password' => bcrypt('change_this'),
        ]);


        $user_b = User::factory()->create([
            'first_name' => 'Valentine',
            'last_name' => 'Vuksani',
            'email' => 'valentinevuksani2022@gmail.com',
            'password' => bcrypt('change_that'),
        ]);

        //Contact pool global
        $contacts = Contact::factory(15)->create();

        //Creation de 4 projets sur base d'un array de noms
        $project_titles = ['CV', 'Site Client', 'PFE', 'Portfolio',];

        collect($project_titles)->map(function ($title) {
            return Project::factory()->create([
                'title' => $title,
            ]);
        });

        $projects = Project::all();

        //Creation de 3 jiris sur base d'un array de noms
        $jiri_names = ['Jiri 1', 'Jiri 2', 'Jiri 3'];

        collect($jiri_names)->map(function ($name) {
            return Jiri::factory()->create([
                'name' => $name,
            ]);
        });

        $jiris = Jiri::all();

        //On attache les projets aux jiris
        $jiris->each(function ($jiri) use ($projects, $contacts) {
            $jiri->projects()->attach(
                $project_ids = $projects->random(rand(1, $projects->count()))->pluck('id')->toArray()
            );

            $assignment_ids = Assignment::where('jiri_id', $jiri->id)->pluck('id');

            $participants = $contacts->random(rand(6, $contacts->count()));

            //On attache les participants aux jiris
            $syncAttendances = [];

            foreach ($participants as $participant) {
                $randomRole = fake()->randomElement([
                    ContactRoles::Evaluator->value,
                    ContactRoles::Evaluated->value,
                ]);

                $syncAttendances[$participant->id] = ['role' => $randomRole];
            }
            $jiri->contacts()->sync($syncAttendances);

            //On attache les devoirs aux contacts
            if ($assignment_ids->isNotEmpty()) {
                foreach ($syncAttendances as $contactId => $pivot) {
                    if ($pivot['role'] === ContactRoles::Evaluated->value) {
                        $attachAssignment = $assignment_ids->random(rand(1, min(2, $assignment_ids->count())))->all();

                        if ($contact = Contact::find($contactId)) {
                            $contact->assignments()->syncWithoutDetaching($attachAssignment);
                        }
                    }
                }
            }
        });
    }
}
