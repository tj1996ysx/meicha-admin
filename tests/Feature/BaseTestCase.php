<?php

namespace Tests\Feature;

use App\Models\Shopper;
use App\User;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

abstract class BaseTestCase extends TestCase
{

    /**
     * @var User
     */
    protected $user;

    public function login($shopper = null)
    {
        if (!$shopper) {
            $shopper  = factory(Shopper::class)->create();
        }

        $this->actingAs($shopper);
        return $shopper;
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $driver
     *
     * @return $this
     */
    public function actingAs(UserContract $user, $driver = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string  $content
     *
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        if ($this->user) {
            $token = 'Bearer '.\JWTAuth::fromUser($this->user);
            $server[ 'HTTP_AUTHORIZATION' ] = $token;
        }
        $server[ 'HTTP_ACCEPT' ] = 'application/json';

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

}
