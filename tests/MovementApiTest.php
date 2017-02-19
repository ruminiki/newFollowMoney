<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovementApiTest extends TestCase
{
    use MakeMovementTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMovement()
    {
        $movement = $this->fakeMovementData();
        $this->json('POST', '/api/v1/movements', $movement);

        $this->assertApiResponse($movement);
    }

    /**
     * @test
     */
    public function testReadMovement()
    {
        $movement = $this->makeMovement();
        $this->json('GET', '/api/v1/movements/'.$movement->id);

        $this->assertApiResponse($movement->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMovement()
    {
        $movement = $this->makeMovement();
        $editedMovement = $this->fakeMovementData();

        $this->json('PUT', '/api/v1/movements/'.$movement->id, $editedMovement);

        $this->assertApiResponse($editedMovement);
    }

    /**
     * @test
     */
    public function testDeleteMovement()
    {
        $movement = $this->makeMovement();
        $this->json('DELETE', '/api/v1/movements/'.$movement->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/movements/'.$movement->id);

        $this->assertResponseStatus(404);
    }
}
