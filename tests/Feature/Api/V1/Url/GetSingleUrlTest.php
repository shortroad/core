<?php

namespace Tests\Feature\Api\V1\Url;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Contracts\Api\V1\TokenAuthenticateInterface;
use Tests\Feature\Api\V1\Auth\TokenAuthenticate;
use Tests\TestCase;

class GetSingleUrlTest extends TestCase implements TokenAuthenticateInterface
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
        $this->route = route('api.v1.url.single', '');
        $this->route_method = 'GET';
        $this->setToken();
    }

    /**
     * User can get url data
     *
     * @return void
     */
    public function test_user_can_get_url_data()
    {
        $url = Url::factory()->create(['user_id' => $this->user->id]);

        $response = $this->json($this->route_method . $url->path, $this->route, [], ['HTTP_Authorization' => $this->header_token]);

        $response_data = $response->decodeResponseJson()['data'];

        $this->assertEquals(\url($url->path), $response_data['path']);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'target',
                'path'
            ],
            'success'
        ]);
    }

    /**
     * User can't get other user urls
     *
     * @return void
     */
    public function test_user_cant_get_other_user_urls_data()
    {
        $other_user_url = Url::factory()->create();

        $response = $this->json($this->route_method . $other_user_url->path, $this->route, [], ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ],
            'success'
        ]);
    }
}
