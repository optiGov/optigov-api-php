<?php

namespace OptiGov\Responsibilities;

use OptiGov\Client;
use OptiGov\Exceptions\CurlException;
use OptiGov\Exceptions\GraphQLException;
use OptiGov\Exceptions\IOException;
use OptiGov\Exceptions\JSONException;

class GlobalResponsibility extends Responsibility
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return array|null
     */
    public function dienstleistung(int $id): array|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/dienstleistung.graphql"
                )
            )
        )["dienstleistung"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return string|null
     */
    public function dienstleistungName(int $id): string|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/dienstleistung_name.graphql"
                )
            )
        )["dienstleistung"]["leistungsbezeichnung"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return array|null
     */
    public function themenfeld(int $id): array|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/themenfeld.graphql"
                )
            )
        )["themenfeld"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return string|null
     */
    public function themenfeldName(int $id): string|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/themenfeld_name.graphql"
                )
            )
        )["themenfeld"]["name"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return array|null
     */
    public function einrichtung(int $id): array|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/einrichtung.graphql"
                )
            )
        )["einrichtung"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return string|null
     */
    public function einrichtungName(int $id): string|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/einrichtung_name.graphql"
                )
            )
        )["einrichtung"]["name"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return array|null
     */
    public function mitarbeiter(int $id): array|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/mitarbeiter.graphql"
                )
            )
        )["mitarbeiter"];
    }

    /**
     * @param int $id
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return array|null
     */
    public function mitarbeiterName(int $id): string|null
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/mitarbeiter_name.graphql"
                )
            )
        )["mitarbeiter"]["name"];
    }

    /**
     * @param string $weiterleitung_url
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @return array|null
     */
    public function loginBuerger(string $weiterleitung_url): array|null
    {
        return $this->handle($this->request()
            ->setVariable("weiterleitung_url", $weiterleitung_url)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Global/login.graphql"
                )
            )
        )["loginBuerger"];
    }
}