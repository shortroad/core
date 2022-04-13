<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Contracts\Api\V1\TokenAuthenticateInterface;
use Tests\TestCase;

class WhoAmITest extends TestCase implements TokenAuthenticateInterface
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
        $this->route = route('api.v1.auth.whoami');
        $this->route_method = 'GET';
        $this->setToken();
    }

    /**
     * Route should return user data.
     *
     * @return void
     */
    public function test_route_should_return_user_data()
    {

        $response = $this->json('GET', $this->route, [], ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'name',
                'email'
            ],
            'success'
        ]);
    }
}
