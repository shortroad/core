<?php

namespace Tests\Feature\Api\V1\Url;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Contracts\Api\V1\TokenAuthenticateInterface;
use Tests\Feature\Api\V1\Auth\TokenAuthenticate;
use Tests\TestCase;

class DeleteSingleUrlTest extends TestCase implements TokenAuthenticateInterface
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
        $this->route = route('api.v1.url.single', 'for-auth-route-test');
        $this->route_method = 'DELETE';
        $this->setToken();
    }

    /**
     * User can delete own url
     *
     * @return void
     */
    public function test_user_can_delete_own_url()
    {
        $url = Url::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(
            rtrim($this->route, 'for-auth-route-test') . ltrim($url->path, \url('')),
            [],
            ['HTTP_Authorization' => $this->header_token]
        );

        $response_data = $response->decodeResponseJson()['data'];

        $this->assertNull(Url::find($url->id));

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
     * User can't delete other user url
     *
     * @return void
     */
    public function test_user_cant_delete_other_user_url()
    {
        $other_user_url = Url::factory()->create();

        $response = $this->deleteJson(
            rtrim($this->route, 'for-auth-route-test') . ltrim($other_user_url->path, \url('')),
            [],
            ['HTTP_Authorization' => $this->header_token]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'path'
            ],
            'success'
        ]);
    }

    /**
     * User can't delete undefined url
     *
     * @return void
     */
    public function test_user_cant_delete_undefined_url()
    {
        $response = $this->deleteJson(
            rtrim($this->route, 'for-auth-route-test') . 'notFound',
            ['HTTP_Authorization' => $this->header_token]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'path'
            ],
            'success'
        ]);
    }
}
