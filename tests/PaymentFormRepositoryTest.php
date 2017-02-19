<?php

use App\Models\PaymentForm;
use App\Repositories\PaymentFormRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentFormRepositoryTest extends TestCase
{
    use MakePaymentFormTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaymentFormRepository
     */
    protected $paymentFormRepo;

    public function setUp()
    {
        parent::setUp();
        $this->paymentFormRepo = App::make(PaymentFormRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePaymentForm()
    {
        $paymentForm = $this->fakePaymentFormData();
        $createdPaymentForm = $this->paymentFormRepo->create($paymentForm);
        $createdPaymentForm = $createdPaymentForm->toArray();
        $this->assertArrayHasKey('id', $createdPaymentForm);
        $this->assertNotNull($createdPaymentForm['id'], 'Created PaymentForm must have id specified');
        $this->assertNotNull(PaymentForm::find($createdPaymentForm['id']), 'PaymentForm with given id must be in DB');
        $this->assertModelData($paymentForm, $createdPaymentForm);
    }

    /**
     * @test read
     */
    public function testReadPaymentForm()
    {
        $paymentForm = $this->makePaymentForm();
        $dbPaymentForm = $this->paymentFormRepo->find($paymentForm->id);
        $dbPaymentForm = $dbPaymentForm->toArray();
        $this->assertModelData($paymentForm->toArray(), $dbPaymentForm);
    }

    /**
     * @test update
     */
    public function testUpdatePaymentForm()
    {
        $paymentForm = $this->makePaymentForm();
        $fakePaymentForm = $this->fakePaymentFormData();
        $updatedPaymentForm = $this->paymentFormRepo->update($fakePaymentForm, $paymentForm->id);
        $this->assertModelData($fakePaymentForm, $updatedPaymentForm->toArray());
        $dbPaymentForm = $this->paymentFormRepo->find($paymentForm->id);
        $this->assertModelData($fakePaymentForm, $dbPaymentForm->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePaymentForm()
    {
        $paymentForm = $this->makePaymentForm();
        $resp = $this->paymentFormRepo->delete($paymentForm->id);
        $this->assertTrue($resp);
        $this->assertNull(PaymentForm::find($paymentForm->id), 'PaymentForm should not exist in DB');
    }
}
