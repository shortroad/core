<?php

namespace Tests\Feature\Api\V1\Url;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Contracts\Api\V1\TokenAuthenticateInterface;
use Tests\Feature\Api\V1\Auth\TokenAuthenticate;
use Tests\TestCase;

class CreateShortUrlTest extends TestCase implements TokenAuthenticateInterface
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
        $this->route = route('api.v1.url.create');
        $this->route_method = 'POST';
        $this->setToken();
    }

    /**
     * User can't create short url without data
     *
     * @return void
     */
    public function test_user_cant_create_short_url_without_data()
    {
        $url_data = [];

        $response = $this->postJson($this->route, $url_data, ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'target'
            ],
            'success',
        ]);
    }

    /**
     * User can't create short url without target url
     *
     * @return void
     */
    public function test_user_cant_create_short_url_without_target_url()
    {
        $url_data = [];

        $response = $this->postJson($this->route, $url_data, ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'target'
            ],
            'success',
        ]);
    }

    /**
     * User can't create short url with target url without url format
     *
     * @return void
     */
    public function test_user_cant_create_short_url_with_target_url_without_url_format()
    {
        $url_data = [
            'target' => 'wrong'
        ];

        $response = $this->postJson($this->route, $url_data, ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'target'
            ],
            'success',
        ]);
    }

    /**
     * User can't create short url with target url less than 6 character
     *
     * @return void
     */
    public function test_user_cant_create_short_url_with_target_url_less_thatn_6_character()
    {
        $url_data = [
            'target' => 'lo.ir'
        ];

        $response = $this->postJson($this->route, $url_data, ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'target'
            ],
            'success',
        ]);
    }

    /**
     * User can create short url with right target
     *
     * @return void
     */
    public function test_user_can_create_short_url_with_right_target()
    {
        $time = time();

        $url_data = [
            'target' => 'https://mgazori.com/' . $time
        ];

        $response = $this->postJson($this->route, $url_data, ['HTTP_Authorization' => $this->header_token]);

        $response->assertStatus(Response::HTTP_CREATED);

        $url = Url::where('target', $url_data['target'])->first();

        $this->assertNotNull($url);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'path',
                'target'
            ],
            'success',
        ]);
    }
}
