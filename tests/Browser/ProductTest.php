<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductTest extends DuskTestCase
{
    // use DatabaseMigrations;

    protected function login(Browser $browser)
    {
        $browser->visit('/login')
                ->type('email', 'test@mail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/dashboard');
    }

    public function testCreateProduct()
    {
        $this->browse(function (Browser $browser) {

            $this->login($browser);

            $productName = 'Product ' . substr(md5(mt_rand()), 0, 7);
            $productDescription = 'Description for ' . $productName;
            sleep(1);
            $browser->visit('/products')
                    ->waitForLink('Create Product')
                    ->clickLink('Create Product')
                    ->waitForInput('name')
                    ->type('name', $productName)
                    ->waitForInput('description')
                    ->type('description', $productDescription)
                    ->press('Create')
                    ->assertSee('Product added successfully');

            $browser->assertSee($productName);
        });
    }

    public function testViewProduct()
    {
        $this->browse(function (Browser $browser) {
            // $this->login($browser);

            // $product = Product::factory()->create();

            $latestProduct = Product::orderBy('id', 'desc')->first();

            sleep(1);
            $browser->visit('/products')
                    ->assertSee($latestProduct->id);
                    sleep(1);
        });
    }

    public function testEditProduct()
    {
        $this->browse(function (Browser $browser) {

            $latestProduct = Product::orderBy('id', 'desc')->first();
            sleep(1);
            $updatedProductName = 'Updated ' . substr(md5(mt_rand()), 0, 7);

            $browser->visit('/products')
                    ->click('#edit-' . $latestProduct->id)
                    ->type('name', $updatedProductName)
                    ->press('Update')
                    ->assertPathIs('/products/' . $latestProduct->id . '/edit')
                    ->assertInputValue('name', $updatedProductName);
            sleep(1);
            $browser->type('name', '')
                    ->press('Update')
                    ->assertSee('The name field is required.');
            sleep(1);
        });
    }

    public function testDeleteProduct()
    {
        $this->browse(function (Browser $browser) {

            $latestProduct = Product::orderBy('id', 'desc')->first();
            sleep(1);

            $browser->visit('/products')
                    ->press('#delete-' . $latestProduct->id)
                    ->assertPathIs('/products')
                    ->assertDontSee($latestProduct->id)
                    ->assertSee('Product deleted successfully');

            sleep(1);
        });
    }
}
