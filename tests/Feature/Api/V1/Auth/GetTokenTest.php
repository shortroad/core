<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GetTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User can't get token without data
     *
     * @return void
     */
    public function test_user_cant_get_token_without_data()
    {
        $user_data = [];
        $response = $this->getJson(route('api.v1.auth.token'),$user_data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors'=>[
                'email',
                'password'
            ],
            'success',
        ]);
    }

    /**
     * User can't get token with wrong email format
     *
     * @return void
     */
    public function test_user_cant_get_token_with_wrong_email_format()
    {
        $user_data = [
            'email' => 'wrong',
            'password' => 12345678
        ];
        $response = $this->getJson(route('api.v1.auth.token'),$user_data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors'=>[
                'email'
            ],
            'success',
        ]);
    }

    /**
     * User can't get token without registration
     *
     * @return void
     */
    public function test_user_cant_get_token_without_registration()
    {
        $user_data = [
            'email' => 'info@mgazori.com',
            'password' => 12345678
        ];

        $response = $this->getJson(route('api.v1.auth.token'),$user_data);

        $user = User::where('email',$user_data['email'])->first();

        $this->assertNull($user);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        $response->assertJsonStructure([
            'message',
            'errors'=>[
                'email'
            ],
            'success',
        ]);
    }

    /**
     * User can't get token with wrong password
     *
     * @return void
     */
    public function test_user_cant_get_token_with_wrong_password()
    {
        $user_data = [
            'email' => 'info@mgazori.com',
            'password' => 'wrong'
        ];

        $response = $this->getJson(route('api.v1.auth.token'),$user_data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJsonStructure([
            'message',
            'errors'=>[
                'credential'
            ],
            'success',
        ]);
    }

    /**
     * User can get token with right data
     *
     * @return void
     */
    public function test_user_can_get_token_with_right_data()
    {
        $user_data = [
            'name' => 'Mohammad Gazori',
            'email' => 'info@mgazori.com',
            'password' => 12345687
        ];

        User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'password' => Hash::make($user_data['password']),
        ]);

        $response = $this->getJson(route('api.v1.auth.token'),$user_data);

        $this->assertTrue();

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'message',
            'data'=>[
                'token'
            ],
            'success',
        ]);
    }

}
