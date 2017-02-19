<?php

use Faker\Factory as Faker;
use App\Models\Movement;
use App\Repositories\MovementRepository;

trait MakeMovementTrait
{
    /**
     * Create fake instance of Movement and save it in database
     *
     * @param array $movementFields
     * @return Movement
     */
    public function makeMovement($movementFields = [])
    {
        /** @var MovementRepository $movementRepo */
        $movementRepo = App::make(MovementRepository::class);
        $theme = $this->fakeMovementData($movementFields);
        return $movementRepo->create($theme);
    }

    /**
     * Get fake instance of Movement
     *
     * @param array $movementFields
     * @return Movement
     */
    public function fakeMovement($movementFields = [])
    {
        return new Movement($this->fakeMovementData($movementFields));
    }

    /**
     * Get fake data of Movement
     *
     * @param array $postFields
     * @return array
     */
    public function fakeMovementData($movementFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'description' => $fake->word,
            'emission_date' => $fake->word,
            'maturity_date' => $fake->word,
            'value' => $fake->word,
            'operation' => $fake->word,
            'status' => $fake->word,
            'portion_number' => $fake->word,
            'transfer_hash' => $fake->word,
            'portion_hash' => $fake->word,
            'payment_form_id' => $fake->randomDigitNotNull,
            'category_id' => $fake->randomDigitNotNull,
            'bank_account_id' => $fake->randomDigitNotNull,
            'movement_origin_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull
        ], $movementFields);
    }
}
