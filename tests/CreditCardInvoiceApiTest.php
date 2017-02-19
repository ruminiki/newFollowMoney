<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreditCardInvoiceApiTest extends TestCase
{
    use MakeCreditCardInvoiceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateCreditCardInvoice()
    {
        $creditCardInvoice = $this->fakeCreditCardInvoiceData();
        $this->json('POST', '/api/v1/creditCardInvoices', $creditCardInvoice);

        $this->assertApiResponse($creditCardInvoice);
    }

    /**
     * @test
     */
    public function testReadCreditCardInvoice()
    {
        $creditCardInvoice = $this->makeCreditCardInvoice();
        $this->json('GET', '/api/v1/creditCardInvoices/'.$creditCardInvoice->id);

        $this->assertApiResponse($creditCardInvoice->toArray());
    }

    /**
     * @test
     */
    public function testUpdateCreditCardInvoice()
    {
        $creditCardInvoice = $this->makeCreditCardInvoice();
        $editedCreditCardInvoice = $this->fakeCreditCardInvoiceData();

        $this->json('PUT', '/api/v1/creditCardInvoices/'.$creditCardInvoice->id, $editedCreditCardInvoice);

        $this->assertApiResponse($editedCreditCardInvoice);
    }

    /**
     * @test
     */
    public function testDeleteCreditCardInvoice()
    {
        $creditCardInvoice = $this->makeCreditCardInvoice();
        $this->json('DELETE', '/api/v1/creditCardInvoices/'.$creditCardInvoice->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/creditCardInvoices/'.$creditCardInvoice->id);

        $this->assertResponseStatus(404);
    }
}
