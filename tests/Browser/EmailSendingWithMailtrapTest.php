<?php

namespace Tests\Browser;

use App\Services\MailtrapApi;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EmailSendingWithMailtrapTest extends DuskTestCase
{
    public function testEmailSendingWithMailtrap()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/send-email')
                    ->type('recipient', 'test@example.com')
                    ->type('subject', 'Test Subject')
                    ->type('content', 'Test Content')
                    ->press('Send Email');

            // Wait for the email to be processed by Mailtrap
            sleep(5);

            // Use Mailtrap API to get the email that you sent just now
            $mailTrapApi = new MailtrapApi();
            $latestEmail = $mailTrapApi->getLatestEmail();

            if ($latestEmail === null) {
                $this->fail('No email found in Mailtrap. Debug info: ' . $mailTrapApi->getLastError());
            }

            $this->assertEquals('test@example.com', $latestEmail->to_email);
            $this->assertEquals('Test Subject', $latestEmail->subject);
            // Format the content as it would be in the email
            $formattedContent = nl2br(e('Test Content'));
            $this->assertStringContainsString($formattedContent, $latestEmail->html_body);

            $browser->assertSee('Email sent successfully');
        });
    }
}
