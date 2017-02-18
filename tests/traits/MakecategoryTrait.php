<?php

use Faker\Factory as Faker;
use App\Models\category;
use App\Repositories\categoryRepository;

trait MakecategoryTrait
{
    /**
     * Create fake instance of category and save it in database
     *
     * @param array $categoryFields
     * @return category
     */
    public function makecategory($categoryFields = [])
    {
        /** @var categoryRepository $categoryRepo */
        $categoryRepo = App::make(categoryRepository::class);
        $theme = $this->fakecategoryData($categoryFields);
        return $categoryRepo->create($theme);
    }

    /**
     * Get fake instance of category
     *
     * @param array $categoryFields
     * @return category
     */
    public function fakecategory($categoryFields = [])
    {
        return new category($this->fakecategoryData($categoryFields));
    }

    /**
     * Get fake data of category
     *
     * @param array $postFields
     * @return array
     */
    public function fakecategoryData($categoryFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'description' => $fake->word,
            'category_superior_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull
        ], $categoryFields);
    }
}
