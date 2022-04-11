<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends TestCase
{

    /**
     * Registration function should not be processed without data
     *
     * @return void
     */
    public function test_registration_function_should_not_processed_without_data()
    {
        $data =[];

        $response = $this->post(route('api.v1.auth.register',$data));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'success',
            'message',
            'errors'=>[
                'name'
            ],
        ]);
    }

    /**
     * User can't register with less than five char name
     *
     * @return void
     */
    public function test_user_cant_register_with_less_than_five_char_name()
    {
        $user_data = [
            'name' => 'Moha',
            'email' => 'info@mgazori.com',
            'password' => '12345678'
        ];

        $response = $this->post(route('api.v1.auth.register'), $user_data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'success',
            'message',
            'errors'=>[
                'name'
            ],
        ]);
    }
    /**
     * User can't register with wrong email address
     *
     * @return void
     */
    public function test_user_cant_register_with_wrong_email_address()
    {
        $user_data = [
            'name' => 'Mohammad Gazori',
            'email' => 'info',
            'password' => '12345678'
        ];

        $response = $this->post(route('api.v1.auth.register'),$user_data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'success',
            'message',
            'errors'=>[
                'email'
            ],
        ]);
    }

    /**
     * User can't register with less than eight char password
     *
     * @return void
     */
    public function test_user_cant_register_with_less_than_eight_char_password()
    {
        $user_data = [
            'name' => 'Mohammad Gazori',
            'email' => 'info@mgazori.com',
            'password' => '123'
        ];

        $response = $this->post(route('api.v1.auth.register'),$user_data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'success',
            'message',
            'errors'=>[
                'password'
            ],
        ]);

    }

    /**
     * User should be registered successfully with right data
     *
     * @return void
     */
    public function test_user_can_register_with_right_data()
    {
        $user_data = [
            'name' => 'Mohammad Gazori',
            'email' => 'info@mgazori.com',
            'password' => '12345678'
        ];

        $response = $this->post(route('api.v1.auth.register'),$user_data);

        $response->assertCreated();

        $response->assertJsonStructure([
            'success',
            'message',
            'data'=>[
                'name',
                'email'
            ],
        ]);
    }
}
