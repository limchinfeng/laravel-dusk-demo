<?php

namespace Tests\Browser;

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
                ->assertPathIs('/dashboard');   // check whether the user is redirected to the dashboard
    }

    public function testCreateProduct()
    {
        $this->browse(function (Browser $browser) {

            // Login
            $this->login($browser);

            // Generate random product name and description
            $productName = 'Product ' . substr(md5(mt_rand()), 0, 7);
            $productDescription = 'Description for ' . $productName;
            sleep(1);

            // Create a new product
            $browser->visit('/products')
              ->waitForLink('Create Product') // wait for the 'Create Product' link to appear (Will throw an error if the link is not found within 5 seconds)
                    ->clickLink('Create Product')
                    ->waitForInput('name')
                    ->type('name', $productName)
                    ->waitForInput('description')
                    ->type('description', $productDescription)
              ->press('Create')   // click the 'Create' button
                    ->assertSee('Product added successfully');  // check for the success message

            $browser->assertSee($productName);  // check whether the created product name is displayed on the page
        });
    }

    public function testViewProduct()
    {
        $this->browse(function (Browser $browser) {
            // $product = Product::factory()->create();

            // Find the latest product using eloquent
            $latestProduct = Product::orderBy('id', 'desc')->first();

            sleep(1);

            // Visit the products page and check whether the latest product id is displayed
            $browser->visit('/products')
                    ->assertSee($latestProduct->id);

            sleep(1);
        });
    }

    public function testEditProduct()
    {
        $this->browse(function (Browser $browser) {

            // Find the latest product using eloquent
            $latestProduct = Product::orderBy('id', 'desc')->first();
            sleep(1);

            // Generate a random product name
            $updatedProductName = 'Updated ' . substr(md5(mt_rand()), 0, 7);

            // Edit the latest product
            $browser->visit('/products')
                    ->click('#edit-' . $latestProduct->id) // click the edit button with the id of the latest product
                    ->assertPathIs('/products/' . $latestProduct->id . '/edit') // check whether the user is redirected to the latest product edit page (/products/:id/edit)
                    ->type('name', $updatedProductName)    // update the product name
                    ->press('Update')
                    ->assertSee('Product updated successfully');  // check for the success message
            sleep(1);

            // Check for error input validation
            $browser->type('name', '')
                    ->press('Update')
                    ->assertSee('The name field is required.'); // check for the error message

            sleep(1);
        });
    }

    public function testDeleteProduct()
    {
        $this->browse(function (Browser $browser) {

            // Find the latest product using eloquent
            $latestProduct = Product::orderBy('id', 'desc')->first();
            sleep(1);

            // Delete the latest product
            $browser->visit('/products')
                    ->waitFor('#delete-' . $latestProduct->id)  // wait for the delete button with the id of the latest product to appear (Will throw an error if the button is not found within 5 seconds
                    ->press('#delete-' . $latestProduct->id)      // click the delete button with the id of the latest product
                    ->assertPathIs('/products')
                    ->assertDontSee($latestProduct->id)             // check whether the latest product id is not displayed on the page
                    ->assertSee('Product deleted successfully');    // check for the success message

            sleep(1);
        });
    }
}
