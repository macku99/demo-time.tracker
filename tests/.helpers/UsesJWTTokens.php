<?php

use App\DataModels\User\User;

trait UsesJWTTokens
{

    /**
     * @var User
     */
    protected $authUser = null;

    /**
     * @var string
     */
    protected $authUserToken = null;

    /**
     * Visit the given URI with a JSON request that includes the JWT token into headers.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array  $data
     * @param  array  $headers
     * @return $this
     */
    public function jwtJson($method, $uri, array $data = [], array $headers = [])
    {
        $content = json_encode($data);

        $headers = array_merge([
            'Authorization'  => 'Bearer ' . $this->getAuthUserToken(),
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE'   => 'application/json',
            'Accept'         => 'application/json',
        ], $headers);

        $this->call(
            $method, $uri, $data, [], [], $this->transformHeadersToServerVars($headers), $content
        );

        return $this;
    }

    /**
     * @param  User $user
     * @return $this
     */
    protected function withAuthUser(User $user)
    {
        $this->authUser = $user;

        return $this;
    }

    /**
     * @return string
     */
    protected function getAuthUserToken()
    {
        if ( ! $this->authUser) {
            $this->authUser = factory(User::class, 'admin')->create();
        }

        $JWTAuth = app('tymon.jwt.auth');

        return $JWTAuth->fromUser($this->authUser);
    }

}