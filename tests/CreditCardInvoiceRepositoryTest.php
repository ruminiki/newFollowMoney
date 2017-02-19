<?php

use App\Models\CreditCardInvoice;
use App\Repositories\CreditCardInvoiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreditCardInvoiceRepositoryTest extends TestCase
{
    use MakeCreditCardInvoiceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var CreditCardInvoiceRepository
     */
    protected $creditCardInvoiceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->creditCardInvoiceRepo = App::make(CreditCardInvoiceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateCreditCardInvoice()
    {
        $creditCardInvoice = $this->fakeCreditCardInvoiceData();
        $createdCreditCardInvoice = $this->creditCardInvoiceRepo->create($creditCardInvoice);
        $createdCreditCardInvoice = $createdCreditCardInvoice->toArray();
        $this->assertArrayHasKey('id', $createdCreditCardInvoice);
        $this->assertNotNull($createdCreditCardInvoice['id'], 'Created CreditCardInvoice must have id specified');
        $this->assertNotNull(CreditCardInvoice::find($createdCreditCardInvoice['id']), 'CreditCardInvoice with given id must be in DB');
        $this->assertModelData($creditCardInvoice, $createdCreditCardInvoice);
    }

    /**
     * @test read
     */
    public function testReadCreditCardInvoice()
    {
        $creditCardInvoice = $this->makeCreditCardInvoice();
        $dbCreditCardInvoice = $this->creditCardInvoiceRepo->find($creditCardInvoice->id);
        $dbCreditCardInvoice = $dbCreditCardInvoice->toArray();
        $this->assertModelData($creditCardInvoice->toArray(), $dbCreditCardInvoice);
    }

    /**
     * @test update
     */
    public function testUpdateCreditCardInvoice()
    {
        $creditCardInvoice = $this->makeCreditCardInvoice();
        $fakeCreditCardInvoice = $this->fakeCreditCardInvoiceData();
        $updatedCreditCardInvoice = $this->creditCardInvoiceRepo->update($fakeCreditCardInvoice, $creditCardInvoice->id);
        $this->assertModelData($fakeCreditCardInvoice, $updatedCreditCardInvoice->toArray());
        $dbCreditCardInvoice = $this->creditCardInvoiceRepo->find($creditCardInvoice->id);
        $this->assertModelData($fakeCreditCardInvoice, $dbCreditCardInvoice->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteCreditCardInvoice()
    {
        $creditCardInvoice = $this->makeCreditCardInvoice();
        $resp = $this->creditCardInvoiceRepo->delete($creditCardInvoice->id);
        $this->assertTrue($resp);
        $this->assertNull(CreditCardInvoice::find($creditCardInvoice->id), 'CreditCardInvoice should not exist in DB');
    }
}
