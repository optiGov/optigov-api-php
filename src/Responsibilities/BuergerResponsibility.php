<?php

namespace OptiGov\Responsibilities;

use OptiGov\Client;
use OptiGov\Exceptions\CurlException;
use OptiGov\Exceptions\GraphQLException;
use OptiGov\Exceptions\IOException;
use OptiGov\Exceptions\JSONException;
use OptiGov\Exceptions\JWTException;
use OptiGov\Support\JWT;

class BuergerResponsibility extends Responsibility
{
    /**
     * @var string
     */
    private string $refresh_token;

    /**
     * @var string|null
     */
    private ?string $access_token = null;

    public function __construct(Client $client, string $refresh_token)
    {
        parent::__construct($client);
        $this->refresh_token = $refresh_token;
        $this->refreshAccessToken();
    }

    /**
     * @return int
     * @throws JWTException
     */
    private function getId(): int
    {
        return (int)JWT::decode($this->refresh_token)["sub"];
    }

    /**
     * @return $this
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     */
    public function refreshAccessToken(): static
    {
        $this->access_token = $this->handle($this->request()
            ->setVariable("refresh_token", $this->refresh_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/aktualisiere_token.graphql"
                )
            )
        )["aktualisiereToken"]["access_token"];
        return $this;
    }

    /**
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    public function alleAntraege(): array
    {
        return $this->handle($this->request()
            ->setVariable("id", $this->getId())
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/antraege.graphql"
                )
            )
        )["buerger"]["antraege"];
    }

    /**
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    public function alleChats(): array
    {
        return $this->handle($this->request()
            ->setVariable("id", $this->getId())
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/chats.graphql"
                )
            )
        )["buerger"]["chats"];
    }

    /**
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    public function alleTermine(): array
    {
        return $this->handle($this->request()
            ->setVariable("id", $this->getId())
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/termine.graphql"
                )
            )
        )["buerger"]["terminvereinbarungen"];
    }

    /**
     * @param int $id
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     */
    public function chat(int $id): array
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/chat.graphql"
                )
            )
        )["chat"];
    }

    /**
     * @param int $formular_id
     * @param string $weiterleitung_url
     * @param array $parameter
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     */
    public function stelleAntrag(int $formular_id, string $weiterleitung_url, array $parameter = []): array
    {
        // format parameters
        $formatted_params = [];
        foreach ($parameter as $key => $value) {
            $formatted_params[] = "$key=$value";
        }

        // make request
        return $this->handle($this->request()
            ->setVariable("formular_id", $formular_id)
            ->setVariable("weiterleitung_url", $weiterleitung_url)
            ->setVariable("parameter", $formatted_params)
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/antrag_stellen.graphql"
                )
            )
        )["erstelleAntragsanfrage"];
    }

    /**
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    public function daten(): array
    {
        return $this->handle($this->request()
            ->setVariable("id", $this->getId())
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/buerger.graphql"
                )
            )
        )["buerger"];
    }

    /**
     * @return bool
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    public function loescheBuerger(): bool
    {
        return $this->handle($this->request()
            ->setVariable("id", $this->getId())
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/buerger_loeschen.graphql"
                )
            )
        )["loescheBuerger"];
    }

    /**
     * @param string $pfad
     * @param string $name
     * @param string $bezeichner
     * @return bool
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     */
    public function dateiHochladen(string $pfad, string $name, string $bezeichner): bool
    {
        return $this->handle($this->request()
            ->setVariable("name", $name)
            ->setVariable("bezeichner", $bezeichner)
            ->addFile("file0", $pfad)
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/datei_hochladen.graphql"
                )
            )
        )["uploadDatei"];
    }

    /**
     * @param string $name
     * @param int|null $antrag_id
     * @param int|null $mitarbeiter_id
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    public function erstelleChat(string $name, ?int $mitarbeiter_id = null, ?int $antrag_id = null): array
    {
        $chatInput = [
            "name" => $name,
            "buerger" => $this->getId(),
            "antrag" => $antrag_id,
            "mitarbeiter" => $mitarbeiter_id,
        ];

        return $this->handle($this->request()
            ->setVariable("chat", $chatInput)
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/erstelle_chat.graphql"
                )
            )
        )["erstelleChat"];
    }

    /**
     * @param string $inhalt
     * @param int $chat_id
     * @param array $dateien
     * @return array
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException|JWTException
     */
    public function sendeNachricht(string $inhalt, int $chat_id, array $dateien = []): array
    {
        $nachrichtInput = [
            "inhalt" => $inhalt,
            "chat" => $chat_id,
            "buerger" => $this->getId(),
            "dateien" => $dateien,
        ];

        return $this->handle($this->request()
            ->setVariable("nachricht", $nachrichtInput)
            ->setAuthorization($this->access_token)
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/sende_nachricht.graphql"
                )
            )
        )["erstelleNachricht"];
    }
}