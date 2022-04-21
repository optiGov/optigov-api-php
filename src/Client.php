<?php

namespace OptiGov;

use OptiGov\GraphQL\Request;
use OptiGov\Responsibilities\BuergerResponsibility;
use OptiGov\Responsibilities\GlobalResponsibility;
use OptiGov\Responsibilities\VerwaltungResponsibility;

/**
 * @method array dienstleistung(int $id)
 * @method string dienstleistungName(int $id)
 * @method string themenfeld(int $id)
 * @method string themenfeldName(int $id)
 * @method array einrichtung(int $id)
 * @method string einrichtungName(int $id)
 * @method array oauthAuthorize(string $clientId, string $redirectUrl, string $scope = "buerger.identity")
 * @method array oauthGetTokens(string $codeVerifier, string $code, string $clientId, string $redirectUrl)‚
 * @method array mitarbeiter(int $id)
 * @method string mitarbeiterName(int $id)
 */
class Client
{
    /**
     * @var string
     */
    private string $apiEndpunkt;

    /**
     * @var string|null
     */
    private string|null $oauthAuthEndpunkt;

    /**
     * @var string|null
     */
    private string|null $oauthTokenEndpunkt;


    /**
     * @param string $apiEndpunkt
     * @param string|null $oauthAuthEndpunkt
     * @param string|null $oauthTokenEndpunkt
     */
    public function __construct(string $apiEndpunkt, string $oauthAuthEndpunkt = null, string $oauthTokenEndpunkt = null)
    {
        $this->apiEndpunkt = $apiEndpunkt;
        $this->oauthAuthEndpunkt = $oauthAuthEndpunkt;
        $this->oauthTokenEndpunkt = $oauthTokenEndpunkt;
    }

    /**
     * @return string
     */
    public function getApiEndpunkt(): string
    {
        return $this->apiEndpunkt;
    }

    /**
     * @return string
     */
    public function getOauthAuthEndpunkt(): string
    {
        return $this->oauthAuthEndpunkt;
    }

    /**
     * @return string
     */
    public function getOauthTokenEndpunkt(): string
    {
        return $this->oauthTokenEndpunkt;
    }

    /**
     * Returns a request instance.
     *
     * @return Request
     */
    public function request(): Request
    {
        return new Request(Request::POST, $this->apiEndpunkt);
    }

    /**
     * @param int $id
     * @return VerwaltungResponsibility
     */
    public function verwaltung(int $id)
    {
        return new VerwaltungResponsibility($this, $id);
    }

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @return BuergerResponsibility
     */
    public function buerger(string $accessToken, string $refreshToken)
    {
        return new BuergerResponsibility($this, $accessToken, $refreshToken);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {

        // forward method call to GlobalResponsibility
        return (new GlobalResponsibility($this))->{$name}(...$arguments);
    }
}