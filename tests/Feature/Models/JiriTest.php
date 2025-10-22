<?php

use App\Enums\ContactRoles;
use App\Models\Contact;
use App\Models\Jiri;
use App\Models\Project;

it('is possible to retrieve many evaluated and many evaluators from a jiri', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Contact::factory()->count(7),
            ['role' => ContactRoles::Evaluated->value]
        )
        ->hasAttached(
            Contact::factory()->count(3),
            ['role' => ContactRoles::Evaluator->value]
        )
        ->create();

    $this->assertDatabaseCount('attendances', 10);
    expect($jiri->evaluators->count())->toBe(3)
        ->and($jiri->evaluated->count())->toBe(7)
        ->and($jiri->contacts->count())->toBe(10);
});

it('is possible to retrieve many projects from a jiri', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(3)
        )
        ->create();

    $this->assertDatabaseCount('assignments', 3);
    expect($jiri->projects->count())->toBe(3);
});

it('is possible to retrieve many assignments from a evaluated', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(3)
        )
        ->hasAttached(
            Contact::factory()->count(1),
            ['role' => ContactRoles::Evaluated->value]
        )
        ->create();

    $contact = $jiri->evaluated()->first();
    $assignments = $jiri->assignments;

    foreach ($assignments as $assignment) {
        $contact->assignments()->attach($assignment->id);
    }

    expect($jiri->assignments->count())->toBe(3)
        ->and($contact->assignments->count())->toBe(3)
        ->and($contact->implementations->count())->toBe(3);

    foreach ($assignments as $assignment) {
        expect($assignment->implementations->count())->toBe(1);
    }

});
