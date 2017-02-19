<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreditCardApiTest extends TestCase
{
    use MakeCreditCardTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateCreditCard()
    {
        $creditCard = $this->fakeCreditCardData();
        $this->json('POST', '/api/v1/creditCards', $creditCard);

        $this->assertApiResponse($creditCard);
    }

    /**
     * @test
     */
    public function testReadCreditCard()
    {
        $creditCard = $this->makeCreditCard();
        $this->json('GET', '/api/v1/creditCards/'.$creditCard->id);

        $this->assertApiResponse($creditCard->toArray());
    }

    /**
     * @test
     */
    public function testUpdateCreditCard()
    {
        $creditCard = $this->makeCreditCard();
        $editedCreditCard = $this->fakeCreditCardData();

        $this->json('PUT', '/api/v1/creditCards/'.$creditCard->id, $editedCreditCard);

        $this->assertApiResponse($editedCreditCard);
    }

    /**
     * @test
     */
    public function testDeleteCreditCard()
    {
        $creditCard = $this->makeCreditCard();
        $this->json('DELETE', '/api/v1/creditCards/'.$creditCard->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/creditCards/'.$creditCard->id);

        $this->assertResponseStatus(404);
    }
}
