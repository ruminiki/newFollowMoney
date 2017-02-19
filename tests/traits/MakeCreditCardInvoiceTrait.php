<?php

use Faker\Factory as Faker;
use App\Models\CreditCardInvoice;
use App\Repositories\CreditCardInvoiceRepository;

trait MakeCreditCardInvoiceTrait
{
    /**
     * Create fake instance of CreditCardInvoice and save it in database
     *
     * @param array $creditCardInvoiceFields
     * @return CreditCardInvoice
     */
    public function makeCreditCardInvoice($creditCardInvoiceFields = [])
    {
        /** @var CreditCardInvoiceRepository $creditCardInvoiceRepo */
        $creditCardInvoiceRepo = App::make(CreditCardInvoiceRepository::class);
        $theme = $this->fakeCreditCardInvoiceData($creditCardInvoiceFields);
        return $creditCardInvoiceRepo->create($theme);
    }

    /**
     * Get fake instance of CreditCardInvoice
     *
     * @param array $creditCardInvoiceFields
     * @return CreditCardInvoice
     */
    public function fakeCreditCardInvoice($creditCardInvoiceFields = [])
    {
        return new CreditCardInvoice($this->fakeCreditCardInvoiceData($creditCardInvoiceFields));
    }

    /**
     * Get fake data of CreditCardInvoice
     *
     * @param array $postFields
     * @return array
     */
    public function fakeCreditCardInvoiceData($creditCardInvoiceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'emission_date' => $fake->word,
            'maturity_date' => $fake->word,
            'value' => $fake->word,
            'amount_paid' => $fake->word,
            'reference_month' => $fake->word,
            'reference_year' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'credit_card_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull
        ], $creditCardInvoiceFields);
    }
}
