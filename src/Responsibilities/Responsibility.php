<?php

namespace OptiGov\Responsibilities;

use OptiGov\Client;
use OptiGov\Exceptions\GraphQLException;
use OptiGov\Exceptions\JSONException;
use OptiGov\GraphQL\Request;
use OptiGov\GraphQL\Response;
use OptiGov\IO\FileReader;

abstract class Responsibility
{
    /**
     * @var Client
     */
    protected Client $client;
    protected FileReader $files;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->files = new FileReader();
    }

    /**
     * @return Request
     */
    protected function request()
    {
        return $this->client->request();
    }

    /**
     * @param Response $response
     * @return array|null
     * @return array|null
     * @throws JSONException
     * @throws GraphQLException
     */
    protected function handle(Response $response): array|null
    {
        if ($response->hasErrors()){
            var_dump($response->errors());
            throw (new GraphQLException(count($response->errors())
                . " GraphQL error(s) occured during execution. "
                . "For more information see \$exception->getErrors()."))
                ->setErrors($response->errors());
        }

        return $response->data();
    }
}