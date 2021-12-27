<?php

namespace OptiGov\Responsibilities;

use OptiGov\Client;
use OptiGov\Exceptions\CurlException;
use OptiGov\Exceptions\GraphQLException;
use OptiGov\Exceptions\IOException;
use OptiGov\Exceptions\JSONException;

class VerwaltungResponsibility extends Responsibility
{
    private int $id;

    public function __construct(Client $client, int $id)
    {
        parent::__construct($client);
        $this->id = $id;
    }

    /**
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
     */
    public function alleDienstleistungen(): array|null
    {
        return $this->handle($this->request()
            ->setVariable("verwaltungsId", $this->id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Verwaltung/dienstleistungen.graphql"
                )
            )
        )["verwaltung"]["dienstleistungen"];
    }

    /**
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
     */
    public function alleDienstleistungskategorien(): array|null
    {
        return $this->handle($this->request()
            ->setVariable("verwaltungsId", $this->id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Verwaltung/dienstleistungskategorien.graphql"
                )
            )
        )["verwaltung"]["dienstleistungskategorien"];
    }

    /**
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
     */
    public function alleEinrichtungen(): array|null
    {
        return $this->handle($this->request()
            ->setVariable("verwaltungsId", $this->id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Verwaltung/einrichtungen.graphql"
                )
            )
        )["verwaltung"]["einrichtungen"];
    }

    /**
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
     */
    public function alleMitarbeiter(): array|null
    {
        return $this->handle($this->request()
            ->setVariable("verwaltungsId", $this->id)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Verwaltung/mitarbeiter.graphql"
                )
            )
        )["verwaltung"]["mitarbeiter"];
    }
}