<?php

namespace OptiGov\GraphQL;

use OptiGov\Exceptions\CurlException;
use OptiGov\Exceptions\JSONException;

class Response
{

    private string $response;

    /**
     * @param string $response
     */
    public function __construct(string $response)
    {
        $this->response = $response;
    }

    /**
     * @throws JSONException
     */
    public function body(): array
    {
        $body = json_decode($this->response, true);
        if (json_last_error() !== JSON_ERROR_NONE)
            throw new JSONException("The content returned by the server is not JSON formatted.");

        return $body;
    }

    /**
     * @return array|null
     * @throws JSONException
     */
    public function data(): array|null
    {
        return $this->body()["data"];
    }

    /**
     * @return array
     * @throws JSONException
     */
    public function errors(): array
    {
        return $this->body()["errors"] ?? [];
    }

    /**
     * @return bool
     * @throws JSONException
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors());
    }
}