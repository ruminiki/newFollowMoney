<?php

use Faker\Factory as Faker;
use App\Models\BankAccount;
use App\Repositories\BankAccountRepository;

trait MakeBankAccountTrait
{
    /**
     * Create fake instance of BankAccount and save it in database
     *
     * @param array $bankAccountFields
     * @return BankAccount
     */
    public function makeBankAccount($bankAccountFields = [])
    {
        /** @var BankAccountRepository $bankAccountRepo */
        $bankAccountRepo = App::make(BankAccountRepository::class);
        $theme = $this->fakeBankAccountData($bankAccountFields);
        return $bankAccountRepo->create($theme);
    }

    /**
     * Get fake instance of BankAccount
     *
     * @param array $bankAccountFields
     * @return BankAccount
     */
    public function fakeBankAccount($bankAccountFields = [])
    {
        return new BankAccount($this->fakeBankAccountData($bankAccountFields));
    }

    /**
     * Get fake data of BankAccount
     *
     * @param array $postFields
     * @return array
     */
    public function fakeBankAccountData($bankAccountFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'description' => $fake->word,
            'number' => $fake->word,
            'user_id' => $fake->randomDigitNotNull
        ], $bankAccountFields);
    }
}
