<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Helpers\JwtToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

trait TokenAuthenticate
{

    public string $token;
    public string $route_method;
    public string $route_name;

    /**
     * Set token value.
     *
     * @return void
     */
    public function setToken(): void
    {
        $user = User::create([
            'name' => 'Mohammad Gazori',
            'email' => 'info@mgazori.com',
            'password' => Hash::make(12345678)
        ]);
        $this->token = JwtToken::generate([
            'user_id' => $user->id,
            'created_at' => time()
        ]);
    }

    /**
     * Route not accessible without token
     *
     * @return void
     */
    public function test_route_not_accessible_without_token()
    {
        $response = $this->json($this->route_method, route($this->route_name));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'token'
            ],
            'success',
        ]);
    }

    /**
     * Route not accessible without Bearer in first of token header
     *
     * @return void
     */
    public function test_route_not_accessible_without_bearer_in_first_of_token_header()
    {
        $response = $this->json($this->route_method, route($this->route_name), ['HTTP_Authorization' => $this->token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'token'
            ],
            'success',
        ]);
    }

    /**
     * Route not accessible with invalid token
     *
     * @return void
     */
    public function test_route_not_accessible_with_invalid_token()
    {
        $response = $this->json($this->route_method, route($this->route_name), ['HTTP_Authorization' => $this->token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'token'
            ],
            'success',
        ]);
    }

    /**
     * Route not accessible with invalid user_id in token
     *
     * @return void
     */
    public function test_route_not_accessible_with_invalid_user_id_in_token()
    {

        User::where('email', 'info@mgazori.com')->delete();

        $response = $this->json($this->route_method, route($this->route_name), ['HTTP_Authorization' => $this->token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'token'
            ],
            'success',
        ]);
    }
}
