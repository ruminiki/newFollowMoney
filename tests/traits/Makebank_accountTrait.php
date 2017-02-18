<?php

use Faker\Factory as Faker;
use App\Models\bank_account;
use App\Repositories\bank_accountRepository;

trait Makebank_accountTrait
{
    /**
     * Create fake instance of bank_account and save it in database
     *
     * @param array $bankAccountFields
     * @return bank_account
     */
    public function makebank_account($bankAccountFields = [])
    {
        /** @var bank_accountRepository $bankAccountRepo */
        $bankAccountRepo = App::make(bank_accountRepository::class);
        $theme = $this->fakebank_accountData($bankAccountFields);
        return $bankAccountRepo->create($theme);
    }

    /**
     * Get fake instance of bank_account
     *
     * @param array $bankAccountFields
     * @return bank_account
     */
    public function fakebank_account($bankAccountFields = [])
    {
        return new bank_account($this->fakebank_accountData($bankAccountFields));
    }

    /**
     * Get fake data of bank_account
     *
     * @param array $postFields
     * @return array
     */
    public function fakebank_accountData($bankAccountFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'description' => $fake->word,
            'number' => $fake->word,
            'user_id' => $fake->randomDigitNotNull
        ], $bankAccountFields);
    }
}
