<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\User;
use App\Models\Session;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Migrate the testing database
        Artisan::call('migrate');

        // Seed the testing database
        Artisan::call('db:seed');
    }

    /**
     * Test the creation of a new invoice with registration, activation, and appointment events.
     *
     * @return void
     */
    public function testCreateInvoiceWithEvents()
    {
        $data = [
            'START' => '2022-01-01',
            'END' => '2022-01-31',
            'CUSTOMER_ID' => 1,
        ];

        $response = $this->postJson('api/Invoice/invoices-create', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['invoice_id']);
    }

    /**
     * Test the creation of a new invoice with only registration events.
     *
     * @return void
     */
    public function testCreateInvoiceWithRegistrationEvents()
    {
        $data = [
            'START' => '2022-01-01',
            'END' => '2022-01-31',
            'CUSTOMER_ID' => 1,
        ];

        // Remove activation and appointment events
        Session::where('user_id', 1)->delete();

        $response = $this->postJson('api/invoices', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['invoice_id']);
    }

    /**
     * Test the retrieval of invoice details.
     *
     * @return void
     */
    public function testGetInvoiceDetails()
    {
        // Assuming you have an existing invoice in the database with ID 1
        $invoiceId = 1;

        $response = $this->getJson("/invoices/{$invoiceId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'start_date',
                'end_date',
                'total_price',
                // Include other expected structure
            ]);
    }

    /**
     * Test the retrieval of non-existing invoice details.
     *
     * @return void
     */
    public function testGetNonExistingInvoiceDetails()
    {
        $nonExistingInvoiceId = 999;

        $response = $this->getJson("/invoices/{$nonExistingInvoiceId}");

        $response->assertStatus(404);
    }
}
