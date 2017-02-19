<?php

use App\Models\CreditCard;
use App\Repositories\CreditCardRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreditCardRepositoryTest extends TestCase
{
    use MakeCreditCardTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var CreditCardRepository
     */
    protected $creditCardRepo;

    public function setUp()
    {
        parent::setUp();
        $this->creditCardRepo = App::make(CreditCardRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateCreditCard()
    {
        $creditCard = $this->fakeCreditCardData();
        $createdCreditCard = $this->creditCardRepo->create($creditCard);
        $createdCreditCard = $createdCreditCard->toArray();
        $this->assertArrayHasKey('id', $createdCreditCard);
        $this->assertNotNull($createdCreditCard['id'], 'Created CreditCard must have id specified');
        $this->assertNotNull(CreditCard::find($createdCreditCard['id']), 'CreditCard with given id must be in DB');
        $this->assertModelData($creditCard, $createdCreditCard);
    }

    /**
     * @test read
     */
    public function testReadCreditCard()
    {
        $creditCard = $this->makeCreditCard();
        $dbCreditCard = $this->creditCardRepo->find($creditCard->id);
        $dbCreditCard = $dbCreditCard->toArray();
        $this->assertModelData($creditCard->toArray(), $dbCreditCard);
    }

    /**
     * @test update
     */
    public function testUpdateCreditCard()
    {
        $creditCard = $this->makeCreditCard();
        $fakeCreditCard = $this->fakeCreditCardData();
        $updatedCreditCard = $this->creditCardRepo->update($fakeCreditCard, $creditCard->id);
        $this->assertModelData($fakeCreditCard, $updatedCreditCard->toArray());
        $dbCreditCard = $this->creditCardRepo->find($creditCard->id);
        $this->assertModelData($fakeCreditCard, $dbCreditCard->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteCreditCard()
    {
        $creditCard = $this->makeCreditCard();
        $resp = $this->creditCardRepo->delete($creditCard->id);
        $this->assertTrue($resp);
        $this->assertNull(CreditCard::find($creditCard->id), 'CreditCard should not exist in DB');
    }
}
