<?php

use App\Models\Movement;
use App\Repositories\MovementRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovementRepositoryTest extends TestCase
{
    use MakeMovementTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MovementRepository
     */
    protected $movementRepo;

    public function setUp()
    {
        parent::setUp();
        $this->movementRepo = App::make(MovementRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMovement()
    {
        $movement = $this->fakeMovementData();
        $createdMovement = $this->movementRepo->create($movement);
        $createdMovement = $createdMovement->toArray();
        $this->assertArrayHasKey('id', $createdMovement);
        $this->assertNotNull($createdMovement['id'], 'Created Movement must have id specified');
        $this->assertNotNull(Movement::find($createdMovement['id']), 'Movement with given id must be in DB');
        $this->assertModelData($movement, $createdMovement);
    }

    /**
     * @test read
     */
    public function testReadMovement()
    {
        $movement = $this->makeMovement();
        $dbMovement = $this->movementRepo->find($movement->id);
        $dbMovement = $dbMovement->toArray();
        $this->assertModelData($movement->toArray(), $dbMovement);
    }

    /**
     * @test update
     */
    public function testUpdateMovement()
    {
        $movement = $this->makeMovement();
        $fakeMovement = $this->fakeMovementData();
        $updatedMovement = $this->movementRepo->update($fakeMovement, $movement->id);
        $this->assertModelData($fakeMovement, $updatedMovement->toArray());
        $dbMovement = $this->movementRepo->find($movement->id);
        $this->assertModelData($fakeMovement, $dbMovement->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMovement()
    {
        $movement = $this->makeMovement();
        $resp = $this->movementRepo->delete($movement->id);
        $this->assertTrue($resp);
        $this->assertNull(Movement::find($movement->id), 'Movement should not exist in DB');
    }
}
