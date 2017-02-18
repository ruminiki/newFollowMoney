<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class categoryApiTest extends TestCase
{
    use MakecategoryTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatecategory()
    {
        $category = $this->fakecategoryData();
        $this->json('POST', '/api/v1/categories', $category);

        $this->assertApiResponse($category);
    }

    /**
     * @test
     */
    public function testReadcategory()
    {
        $category = $this->makecategory();
        $this->json('GET', '/api/v1/categories/'.$category->id);

        $this->assertApiResponse($category->toArray());
    }

    /**
     * @test
     */
    public function testUpdatecategory()
    {
        $category = $this->makecategory();
        $editedcategory = $this->fakecategoryData();

        $this->json('PUT', '/api/v1/categories/'.$category->id, $editedcategory);

        $this->assertApiResponse($editedcategory);
    }

    /**
     * @test
     */
    public function testDeletecategory()
    {
        $category = $this->makecategory();
        $this->json('DELETE', '/api/v1/categories/'.$category->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/categories/'.$category->id);

        $this->assertResponseStatus(404);
    }
}
