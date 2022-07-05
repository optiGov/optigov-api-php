<?php

namespace OptiGov\Responsibilities;

use Closure;
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
    private string $refreshToken;

    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @var int
     */
    private int $clientId;

    /**
     * @var Closure|null
     */
    private ?Closure $onTokenRefreshCallback = null;

    /**
     * @param Client $client
     * @param string|null $accesToken
     * @param string $refreshToken
     * @param int $clientId
     */
    public function __construct(Client $client, ?string $accesToken, string $refreshToken, int $clientId)
    {
        parent::__construct($client);
        $this->accessToken = $accesToken;
        $this->refreshToken = $refreshToken;
        $this->clientId = $clientId;
    }

    /**
     * @param Closure $callback
     */
    public function onTokenRefresh(Closure $callback)
    {
        $this->onTokenRefreshCallback = $callback;
    }

    /**
     * @return int
     * @throws JWTException
     */
    private function getAccountId(): int
    {
        return (int)JWT::decode($this->getAccessToken())["sub"];
    }

    /**
     * @return int
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws JWTException
     */
    private function getId(): int
    {
        return $this->daten()["id"];
    }

    /**
     * @return $this
     */
    public function refreshTokens(): static
    {
        // create request
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->client->getOauthTokenEndpunkt());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        // receive server response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        // close request
        curl_close($ch);

        // get tokens
        $tokens = json_decode($response, true);

        $this->accessToken = $tokens["access_token"];
        $this->refreshToken = $tokens["refresh_token"];

        // call callback if set
        if($this->onTokenRefreshCallback !== null)
            ($this->onTokenRefreshCallback)($this->accessToken, $this->refreshToken);

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        if ($this->accessToken === null)
            $this->refreshTokens();

        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
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
            ->setVariable("id", $this->getAccountId())
            ->setAuthorization($this->getAccessToken())
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/antraege.graphql"
                )
            )
        )["account"]["buerger"]["antraege"];
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
            ->setVariable("id", $this->getAccountId())
            ->setAuthorization($this->getAccessToken())
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/chats.graphql"
                )
            )
        )["account"]["buerger"]["chats"];
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
            ->setVariable("id", $this->getAccountId())
            ->setAuthorization($this->getAccessToken())
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/termine.graphql"
                )
            )
        )["account"]["buerger"]["terminvereinbarungen"];
    }

    /**
     * @param int $id
     * @return array|null
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     */
    public function chat(int $id): ?array
    {
        return $this->handle($this->request()
            ->setVariable("id", $id)
            ->setAuthorization($this->getAccessToken())
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
            ->setAuthorization($this->getAccessToken())
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
            ->setVariable("id", $this->getAccountId())
            ->setAuthorization($this->getAccessToken())
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/account.graphql"
                )
            )
        )["account"]["buerger"];
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
            ->setVariable("id", $this->getAccountId())
            ->setAuthorization($this->getAccessToken())
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
     * @return int
     * @throws CurlException
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     */
    public function dateiHochladen(string $pfad, string $name, string $bezeichner): ?int
    {
        return $this->handle($this->request()
            ->setVariable("name", $name)
            ->setVariable("bezeichner", $bezeichner)
            ->addFile("file0", $pfad)
            ->setAuthorization($this->getAccessToken())
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
            ->setAuthorization($this->getAccessToken())
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
            ->setAuthorization($this->getAccessToken())
            ->execute(
                $this->files->get(__DIR__
                    . "/../Queries/Buerger/sende_nachricht.graphql"
                )
            )
        )["erstelleNachricht"];
    }
}