<?php

use App\Events\JiriCreatedEvent;
use App\Listeners\SendJiriCreatedMailListener;
use App\Models\Jiri;
use App\Models\User;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;

it('fire an event asking to queue an email to send to the author after the creation of a jiri',
    function () {
        Event::fake();
        $user = User::factory()->create();
        actingAs($user);

        $form_data = Jiri::factory()->raw();
        $this->post(route('jiris.store'), $form_data);
        Event::assertListening(
            JiriCreatedEvent::class,
            SendJiriCreatedMailListener::class);
        Event::assertDispatched(JiriCreatedEvent::class);

    });

it('queues the sending of the jiri email after the jiri created event haas been fired', function () {
    Mail::fake();
    $jiri = Jiri::factory()->for(User::factory())->create();

    event(new JiriCreatedEvent($jiri));

    Mail::assertQueued(App\Mail\JiriCreatedMail::class);

});

it('fills correctly the email with the value of the created jiri',
    function () {
        Mail::fake();

        $jiri = Jiri::factory()->for(User::factory())->create();
        $mail = new App\Mail\JiriCreatedMail($jiri);
        $mail->assertSeeInHtml($jiri->name);
    });

it('sends the email using the correct transport layer', function () {

    $user = User::factory()->create();
    actingAs($user);
    $jiri = Jiri::factory()->for($user)->create();
    Mail::to($user->email)->send(new App\Mail\JiriCreatedMail($jiri));
    $response = file_get_contents('http://localhost:8025/api/v1/messages');
    $messages = json_decode($response, true);
    $this->assertNotEmpty($messages['messages']);
});
