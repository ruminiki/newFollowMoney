<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class bank_accountApiTest extends TestCase
{
    use Makebank_accountTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatebank_account()
    {
        $bankAccount = $this->fakebank_accountData();
        $this->json('POST', '/api/v1/bankAccounts', $bankAccount);

        $this->assertApiResponse($bankAccount);
    }

    /**
     * @test
     */
    public function testReadbank_account()
    {
        $bankAccount = $this->makebank_account();
        $this->json('GET', '/api/v1/bankAccounts/'.$bankAccount->id);

        $this->assertApiResponse($bankAccount->toArray());
    }

    /**
     * @test
     */
    public function testUpdatebank_account()
    {
        $bankAccount = $this->makebank_account();
        $editedbank_account = $this->fakebank_accountData();

        $this->json('PUT', '/api/v1/bankAccounts/'.$bankAccount->id, $editedbank_account);

        $this->assertApiResponse($editedbank_account);
    }

    /**
     * @test
     */
    public function testDeletebank_account()
    {
        $bankAccount = $this->makebank_account();
        $this->json('DELETE', '/api/v1/bankAccounts/'.$bankAccount->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/bankAccounts/'.$bankAccount->id);

        $this->assertResponseStatus(404);
    }
}
