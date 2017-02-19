<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentFormApiTest extends TestCase
{
    use MakePaymentFormTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePaymentForm()
    {
        $paymentForm = $this->fakePaymentFormData();
        $this->json('POST', '/api/v1/paymentForms', $paymentForm);

        $this->assertApiResponse($paymentForm);
    }

    /**
     * @test
     */
    public function testReadPaymentForm()
    {
        $paymentForm = $this->makePaymentForm();
        $this->json('GET', '/api/v1/paymentForms/'.$paymentForm->id);

        $this->assertApiResponse($paymentForm->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePaymentForm()
    {
        $paymentForm = $this->makePaymentForm();
        $editedPaymentForm = $this->fakePaymentFormData();

        $this->json('PUT', '/api/v1/paymentForms/'.$paymentForm->id, $editedPaymentForm);

        $this->assertApiResponse($editedPaymentForm);
    }

    /**
     * @test
     */
    public function testDeletePaymentForm()
    {
        $paymentForm = $this->makePaymentForm();
        $this->json('DELETE', '/api/v1/paymentForms/'.$paymentForm->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/paymentForms/'.$paymentForm->id);

        $this->assertResponseStatus(404);
    }
}
