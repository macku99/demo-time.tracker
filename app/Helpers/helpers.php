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

if ( ! function_exists('parse_date_range')) {
    /**
     * Parse a date range.
     *
     * @param  string $dateRange
     * @return array
     */
    function parse_date_range($dateRange)
    {
        if (is_null($dateRange)) {
            return [null, null];
        }

        return array_map(function ($date) {
            $date = trim($date);

            return (new \Carbon\Carbon($date))->format('Y-m-d');
        }, explode('-', $dateRange));
    }
}