<?php

namespace OptiGov\Support;

use OptiGov\Exceptions\JWTException;

class JWT
{

    /**
     * Returns the tokens' content.
     *
     * @param string $token
     * @return array|null
     * @throws JWTException
     */
    public static function decode(string $token): array|null
    {
        // split the token
        $parts = explode(".", $token);
        if (count($parts) !== 3)
            throw new JWTException("The JWT provided is not formatted correctly.");

        [$header, $payload, $signature] = $parts;

        return json_decode(base64_decode($payload), true);
    }

    /**
     * Returns the tokens' content.
     *
     * @param string $token
     * @return array|null
     * @throws JWTException
     */
    public static function decodeHeader(string $token): array|null
    {
        // split the token
        $parts = explode(".", $token);
        if (count($parts) !== 3)
            throw new JWTException("The JWT provided is not formatted correctly.");

        [$header, $payload, $signature] = $parts;

        return json_decode(base64_decode($header), true);
    }
}