<?php

namespace REBELinBLUE\Deployer\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\JWTAuth;

/**
 * Event listener class to create JWT on login.
 */
class CreateJwt
{
    /**
     * @var JWTAuth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle the event.
     *
     * @param Login|\REBELinBLUE\Deployer\Events\JsonWebTokenExpired $event
     */
    public function handle(Login $event)
    {
        $tokenId    = base64_encode(str_random(32));
        $issuedAt   = Carbon::now()->timestamp;
        $notBefore  = $issuedAt;
        $expire     = $notBefore + 3 * 60 * 60; // Adding 3 hours

        // Create the token
        $config = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // JSON Token ID: an unique identifier for the token
            'iss'  => config('app.url'), // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => [                  // Data related to the signed user
                'userId' => $event->user->id    // User ID from the users table
            ],
        ];

        Session::put('jwt', $this->auth->fromUser($event->user, $config));
    }
}
