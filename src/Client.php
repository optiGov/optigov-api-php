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
 * @method array loginBuerger(string $weiterleitung_url)
 * @method array mitarbeiter(int $id)
 * @method string mitarbeiterName(int $id)
 */
class Client
{
    /**
     * @var string
     */
    private string $url;


    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns a request instance.
     *
     * @return Request
     */
    public function request(): Request
    {
        return new Request(Request::POST, $this->url);
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
     * @param string $refresh_token
     * @return BuergerResponsibility
     */
    public function buerger(string $refresh_token)
    {
        return new BuergerResponsibility($this, $refresh_token);
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