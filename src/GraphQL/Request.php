<?php

namespace OptiGov\GraphQL;

use OptiGov\Exceptions\CurlException;

class Request
{
    /*
     * supported http methods
     */
    public const POST = "POST";

    /**
     * private constants
     */
    private const TIMEOUT = 8;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var string[]
     */
    private array $headers;

    /**
     * @var string[]
     */
    private array $files;

    /**
     * @var string[]
     */
    private array $variables;

    /**
     * @param string $method
     * @param string $url
     */
    public function __construct(string $method, string $url)
    {
        $this->url = $url;
        $this->method = $method;
    }

    /**
     * Sets a new header value.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader(string $name, string $value): static
    {
        $this->headers[] = "$name: $value";
        return $this;
    }

    /**
     * Sets the authorization header.
     *
     * @param string $token
     * @return $this
     */
    public function setAuthorization(string $token): static
    {
        return $this->setHeader("Authorization", "Bearer $token");
    }

    /**
     * Adds a new file.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addFile(string $name, string $value): static
    {
        $this->files[$name] = $value;
        return $this;
    }

    /**
     * Sets a new value.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setVariable(string $name, mixed $value): static
    {
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * @param string $request
     * @return Response
     * @throws CurlException
     */
    public function execute(string $request): Response
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, static::TIMEOUT);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($this->method === static::POST)
            curl_setopt($ch, CURLOPT_POST, 1);

        // differentiate between non file queries and file queries
        if (empty($this->files)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                "query" => $request,
                "variables" => $this->variables
            ]));
            // set headers to application/json
            $this->setHeader("Content-Type", "application/json");
        } else {
            // if files exists, add them as post fields
            $postFields = [];
            foreach ($this->files as $key => $file) {
                $postFields[$key] = new \CURLFile($file);
            }
            // add query to postFields
            $postFields["query"] = $request;
            $postFields["variables"] = json_encode($this->variables);

            // execute with post fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

            // set headers to multipart/formdata
            $this->setHeader("Content-Type", "multipart/form-data");
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        $result = curl_exec($ch);

        if ($result === false)
            throw new CurlException("curl_exec() returned false.");

        return new Response((string) $result);
    }
}