<?php

use App\Models\bank_account;
use App\Repositories\bank_accountRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class bank_accountRepositoryTest extends TestCase
{
    use Makebank_accountTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var bank_accountRepository
     */
    protected $bankAccountRepo;

    public function setUp()
    {
        parent::setUp();
        $this->bankAccountRepo = App::make(bank_accountRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatebank_account()
    {
        $bankAccount = $this->fakebank_accountData();
        $createdbank_account = $this->bankAccountRepo->create($bankAccount);
        $createdbank_account = $createdbank_account->toArray();
        $this->assertArrayHasKey('id', $createdbank_account);
        $this->assertNotNull($createdbank_account['id'], 'Created bank_account must have id specified');
        $this->assertNotNull(bank_account::find($createdbank_account['id']), 'bank_account with given id must be in DB');
        $this->assertModelData($bankAccount, $createdbank_account);
    }

    /**
     * @test read
     */
    public function testReadbank_account()
    {
        $bankAccount = $this->makebank_account();
        $dbbank_account = $this->bankAccountRepo->find($bankAccount->id);
        $dbbank_account = $dbbank_account->toArray();
        $this->assertModelData($bankAccount->toArray(), $dbbank_account);
    }

    /**
     * @test update
     */
    public function testUpdatebank_account()
    {
        $bankAccount = $this->makebank_account();
        $fakebank_account = $this->fakebank_accountData();
        $updatedbank_account = $this->bankAccountRepo->update($fakebank_account, $bankAccount->id);
        $this->assertModelData($fakebank_account, $updatedbank_account->toArray());
        $dbbank_account = $this->bankAccountRepo->find($bankAccount->id);
        $this->assertModelData($fakebank_account, $dbbank_account->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletebank_account()
    {
        $bankAccount = $this->makebank_account();
        $resp = $this->bankAccountRepo->delete($bankAccount->id);
        $this->assertTrue($resp);
        $this->assertNull(bank_account::find($bankAccount->id), 'bank_account should not exist in DB');
    }
}
