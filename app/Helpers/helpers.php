<?php

if ( ! function_exists('jwt_token')) {
    /**
     * Get the JWT token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function jwt_token()
    {
        $JWTAuth = app('tymon.jwt.auth');

        if (isset($JWTAuth)) {
            $loggedInUser = request()->user();

            if ( ! is_null($loggedInUser)) {
                return $JWTAuth->fromUser($loggedInUser);
            }

            return null;
        }

        throw new RuntimeException('Application JWT Auth not set.');
    }
}