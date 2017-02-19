<?php

use Faker\Factory as Faker;
use App\Models\CreditCard;
use App\Repositories\CreditCardRepository;

trait MakeCreditCardTrait
{
    /**
     * Create fake instance of CreditCard and save it in database
     *
     * @param array $creditCardFields
     * @return CreditCard
     */
    public function makeCreditCard($creditCardFields = [])
    {
        /** @var CreditCardRepository $creditCardRepo */
        $creditCardRepo = App::make(CreditCardRepository::class);
        $theme = $this->fakeCreditCardData($creditCardFields);
        return $creditCardRepo->create($theme);
    }

    /**
     * Get fake instance of CreditCard
     *
     * @param array $creditCardFields
     * @return CreditCard
     */
    public function fakeCreditCard($creditCardFields = [])
    {
        return new CreditCard($this->fakeCreditCardData($creditCardFields));
    }

    /**
     * Get fake data of CreditCard
     *
     * @param array $postFields
     * @return array
     */
    public function fakeCreditCardData($creditCardFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'description' => $fake->word,
            'limit' => $fake->word,
            'invoice_day' => $fake->word,
            'closing_day' => $fake->word,
            'user_id' => $fake->randomDigitNotNull
        ], $creditCardFields);
    }
}
