<?php

use App\Models\Contact;
use App\Models\Jiri;
use App\Models\Project;
use App\Enums\ContactRoles;

it('can create a contact', function () {
    $contact = Contact::factory()->create(
    );

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
    ]);
});

it('can attend multiple jiris', function () {
    Mail::fake();

    $contact = Contact::factory()
        ->hasAttached(
            Jiri::factory()->count(3),
            ['role' => ContactRoles::Evaluator->value]
        )
        ->create();

    $this->assertDatabaseCount('attendances', 3);
    expect($contact->jiris->count())->toBe(3);
});

it('can be an evaluator in a jiri', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Contact::factory()->count(1),
            ['role' => ContactRoles::Evaluator->value]
        )
        ->create();

    $contact = $jiri->contacts()->first();

    expect($contact->pivot->role)->toBe(ContactRoles::Evaluator->value)
        ->and($jiri->evaluators->count())->toBe(1)
        ->and($jiri->evaluators->first()->id)->toBe($contact->id);
});

it('can be evaluated in a jiri', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Contact::factory()->count(1),
            ['role' => ContactRoles::Evaluated->value]
        )
        ->create();

    $contact = $jiri->contacts()->first();

    expect($contact->pivot->role)->toBe(ContactRoles::Evaluated->value)
        ->and($jiri->evaluated->count())->toBe(1)
        ->and($jiri->evaluated->first()->id)->toBe($contact->id);
});

it('can have multiple implementations across different jiris', function () {
    Mail::fake();

    $contact = Contact::factory()->create();

    $jiri1 = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(2)
        )
        ->hasAttached(
            $contact,
            ['role' => ContactRoles::Evaluated->value]
        )
        ->create();

    $jiri2 = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(1)
        )
        ->hasAttached(
            $contact,
            ['role' => ContactRoles::Evaluated->value]
        )
        ->create();

    // Attacher les assignments au contact
    foreach ($jiri1->assignments as $assignment) {
        $contact->assignments()->attach($assignment->id);
    }

    foreach ($jiri2->assignments as $assignment) {
        $contact->assignments()->attach($assignment->id);
    }

    expect($contact->implementations->count())->toBe(3)
        ->and($contact->assignments->count())->toBe(3);
});

it('can retrieve all assignments from evaluated contact', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(5)
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

    expect($contact->assignments->count())->toBe(5)
        ->and($contact->implementations->count())->toBe(5);
});

it('evaluator contact has no implementations', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(3)
        )
        ->hasAttached(
            Contact::factory()->count(1),
            ['role' => ContactRoles::Evaluator->value]
        )
        ->create();

    $contact = $jiri->evaluators()->first();

    expect($contact->implementations->count())->toBe(0);
});

it('can be deleted and cascade delete implementations', function () {
    Mail::fake();

    $jiri = Jiri::factory()
        ->hasAttached(
            Project::factory()->count(2)
        )
        ->hasAttached(
            Contact::factory()->count(1),
            ['role' => ContactRoles::Evaluated->value]
        )
        ->create();

    $contact = $jiri->evaluated()->first();

    foreach ($jiri->assignments as $assignment) {
        $contact->assignments()->attach($assignment->id);
    }

    $this->assertDatabaseCount('implementations', 2);

    $contact->delete();

    $this->assertDatabaseCount('implementations', 0);
});
