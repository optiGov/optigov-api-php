<?php

namespace OptiGov\Tests\Responsibilities;

use Curfle\Routing\Route;
use OptiGov\Client;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;

class GlobalResponsibilityTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client(BACKEND_URL);
    }

    /**
     * Tests ->dienstleistung() ->dienstleistungName()
     */
    public function testDienstleistung()
    {
        assertNotEmpty($this->client->dienstleistung(1));
        assertNotNull($this->client->dienstleistungName(1));
    }

    /**
     * Tests ->einrichtung() ->einrichtungName()
     */
    public function testErinrichtung()
    {
        assertNotEmpty($this->client->einrichtung(1));
        assertNotNull($this->client->einrichtungName(1));
    }

    /**
     * Tests ->mitarbeiter() ->mitarbeiterName()
     */
    public function testMitarbeiter()
    {
        assertNotEmpty($this->client->mitarbeiter(1));
        assertNotNull($this->client->mitarbeiterName(1));
    }

    /**
     * Tests ->loginBueger()
     */
    public function testLoginBueger()
    {
        assertNotEmpty($this->client->loginBuerger("https://optigov.de"));
    }
}