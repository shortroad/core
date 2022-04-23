<?php

namespace Tests\Feature\Api\V1\Url;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Contracts\Api\V1\TokenAuthenticateInterface;
use Tests\Feature\Api\V1\Auth\TokenAuthenticate;
use Tests\TestCase;

class GetUrlsTest extends TestCase implements TokenAuthenticateInterface
{
    use RefreshDatabase, TokenAuthenticate;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->route = route('api.v1.url.all');
        $this->route_method = 'GET';
        $this->setToken();
    }

    /**
     * User can get all urls data
     *
     * @return void
     */
    public function test_user_can_get_all_urls_data()
    {
        $urls = Url::factory(5)->create(['user_id', $this->user->id]);

        $response = $this->json($this->route_method, $this->route, [], ['HTTP_Authorization' => $this->header_token]);

        $response_data = $response->decodeResponseJson()['data'];

        $this->assertEquals(5, sizeof($response_data));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Get urls should be paginated format
     *
     * @return void
     */
    public function test_get_urls_format_should_be_paginated_format()
    {
        $urls = Url::factory(5)->create(['user_id', $this->user->id]);

        $response = $this->json($this->route_method, $this->route, [], ['HTTP_Authorization' => $this->header_token]);

        $response->assertJsonStructure([
            'success',
            "current_page",
            "data" => [
                '*' => [
                    "path",
                    "target"
                ]
            ],
            "first_page_url",
            "from",
            "last_page",
            "last_page_url",
            "links" => [
                '*' => [
                    "url",
                    "label",
                    "active"
                ],
            ],
            "next_page_url",
            "path",
            "per_page",
            "prev_page_url",
            "to",
            "total"
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * User can't get other user urls
     *
     * @return void
     */
    public function test_user_cant_get_other_user_urls_data()
    {
        $other_user_url = Url::factory()->create();

        $this_user_url = Url::factory(2)->create(['user_id', $this->user->id]);

        $response = $this->json($this->route_method, $this->route, [], ['HTTP_Authorization' => $this->header_token]);

        $this->assertFalse($response->getContent(), \url($other_user_url->path));

        $response->assertStatus(Response::HTTP_OK);
    }
}
