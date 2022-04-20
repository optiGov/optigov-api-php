<?php

namespace OptiGov\Responsibilities;

use Exception;
use OptiGov\Client;
use OptiGov\Exceptions\CurlException;
use OptiGov\Exceptions\GraphQLException;
use OptiGov\Exceptions\IOException;
use OptiGov\Exceptions\JSONException;
use OptiGov\GraphQL\Request;

class GlobalResponsibility extends Responsibility
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    /**
     * @param int $id
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return string|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return string|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return string|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @return array|null
     * @throws GraphQLException
     * @throws IOException
     * @throws JSONException
     * @throws CurlException
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
     * @param string $clientId
     * @param string $redirectUrl
     * @param string $scope
     * @return array
     * @throws Exception
     */
    public function oauthAuthorize(string $clientId, string $redirectUrl, string $scope = "buerger.identity"): array
    {
        // generate a random state for later verification
        $state = bin2hex(random_bytes(20));

        // generate a code verifier
        $codeVerifier = bin2hex(random_bytes(64));

        // create code challenge from code verifier
        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $codeVerifier, true))
            , '='), '+/', '-_');

        // build login query
        $url = $this->client->getOauthAuthEndpunkt() . "?" . http_build_query([
                'client_id' => $clientId,
                'redirect_uri' => $redirectUrl,
                'response_type' => 'code',
                'scope' => $scope,
                'state' => $state,
                'code_challenge' => $codeChallenge,
                'code_challenge_method' => 'S256',
                'type' => 'buerger',
            ]);

        // return information
        return [
            "state" => $state,
            "code_verifier" => $codeVerifier,
            "url" => $url,
        ];
    }

    /**
     * @param string $codeVerifier
     * @param string $code
     * @param string $clientId
     * @param string $redirectUrl
     * @return array
     */
    public function oauthGetTokens(string $codeVerifier, string $code, string $clientId, string $redirectUrl): array
    {
        // create request
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->client->getOauthTokenEndpunkt());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUrl,
            'code_verifier' => $codeVerifier,
            'code' => $code,
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        // receive server response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);


        // close request
        curl_close($ch);

        // return decoded tokens
        return json_decode($response, true);
    }
}