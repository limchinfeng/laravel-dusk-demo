<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function testLogin()
    {
        /**
         * Create a fake user
         */
        // $user = User::factory()->create([
        //     'email' => 'john@example.com',
        //     'password' => bcrypt('password'),
        // ]);

        // $this->browse(function (Browser $browser) use ($user) {
        $this->browse(function (Browser $browser) {
                $browser->visit('/login')
                    ->assertSee('Login')
                    ->type('email', 'test@mail.com')
                    ->type('password', 'password')
                    ->screenshot('login-page-before') // Take a screenshot before actions
                    ->press('LOG IN')
                    ->assertPathIs('/dashboard')
                    ->assertSee("You're logged in!")
                    ->screenshot('login-page-after'); // Take a screenshot after actions
            });;
    }
}
