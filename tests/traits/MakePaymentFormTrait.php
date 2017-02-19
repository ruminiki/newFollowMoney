<?php

use Faker\Factory as Faker;
use App\Models\PaymentForm;
use App\Repositories\PaymentFormRepository;

trait MakePaymentFormTrait
{
    /**
     * Create fake instance of PaymentForm and save it in database
     *
     * @param array $paymentFormFields
     * @return PaymentForm
     */
    public function makePaymentForm($paymentFormFields = [])
    {
        /** @var PaymentFormRepository $paymentFormRepo */
        $paymentFormRepo = App::make(PaymentFormRepository::class);
        $theme = $this->fakePaymentFormData($paymentFormFields);
        return $paymentFormRepo->create($theme);
    }

    /**
     * Get fake instance of PaymentForm
     *
     * @param array $paymentFormFields
     * @return PaymentForm
     */
    public function fakePaymentForm($paymentFormFields = [])
    {
        return new PaymentForm($this->fakePaymentFormData($paymentFormFields));
    }

    /**
     * Get fake data of PaymentForm
     *
     * @param array $postFields
     * @return array
     */
    public function fakePaymentFormData($paymentFormFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'description' => $fake->word,
            'user_id' => $fake->randomDigitNotNull
        ], $paymentFormFields);
    }
}
