<?php

use App\Models\Project;
use App\Models\Jiri;
use App\Models\Contact;
use App\Enums\ContactRoles;

it('can create a project', function () {
    $project = Project::factory()->create();

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
    ]);
});

it('can be assigned to multiple jiris', function () {
    $project = Project::factory()
        ->hasAttached(
            Jiri::factory()->count(3)
        )
        ->create();

    $this->assertDatabaseCount('assignments', 3);
    expect($project->jiris->count())->toBe(3);
});

it('can retrieve assignments from a project', function () {
    $project = Project::factory()
        ->hasAttached(
            Jiri::factory()->count(2)
        )
        ->create();

    expect($project->assignments->count())->toBe(2);
});

it('can retrieve implementations from a project through assignments', function () {
    $jiri = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(1)
        )
        ->hasAttached(
            Contact::factory()->count(2),
            ['role' => ContactRoles::Evaluated->value]
        )
        ->create();

    $project = $jiri->projects()->first();
    $assignment = $project->assignments()->first();
    $contacts = $jiri->evaluated;

    // Créer des implémentations pour chaque contact
    foreach ($contacts as $contact) {
        $contact->assignments()->attach($assignment->id);
    }

    expect($project->assignments->count())->toBe(1)
        ->and($assignment->implementations->count())->toBe(2);
});

it('can be deleted and cascade delete assignments', function () {
    $project = Project::factory()
        ->hasAttached(
            Jiri::factory()->count(2)
        )
        ->create();

    $this->assertDatabaseCount('assignments', 2);

    $project->delete();

    $this->assertDatabaseCount('assignments', 0);
});

