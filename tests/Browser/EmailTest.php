<?php

namespace Tests\Browser;

use App\Mail\ExampleEmail;
use Illuminate\Support\Facades\Mail;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use ProtoneMedia\LaravelDuskFakes\Mails\PersistentMails;

class EmailTest extends DuskTestCase
{
    use PersistentMails;

    public function testEmailSending()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/send-email')
                    ->type('recipient', 'test@example.com')
                    ->type('subject', 'Test Subject')
                    ->type('content', 'Test Content')
                    ->press('Send Email');

            $browser->assertSee('Email sent successfully');

            Mail::to('test@example.com')->send(new ExampleEmail('Test Subject', 'Test Content'));
            Mail::assertSent(ExampleEmail::class, function ($mail) {
                return $mail->hasTo('test@example.com') &&
                       $mail->hasSubject('Test Subject') &&
                       $mail->content === 'Test Content';
            });
        });
    }
}




