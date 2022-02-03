<?php

namespace OptiGov\Tests\Responsibilities;

use Curfle\Routing\Route;
use OptiGov\Client;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;

class BuergerResponsibilityTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client(BACKEND_URL);
    }

    /**
     * Tests ->alleAntraege()
     */
    public function testAlleAntraege()
    {
        assertNotNull($this->client->buerger(REFRESH_TOKEN)->alleAntraege());
    }

    /**
     * Tests ->alleChats()
     */
    public function testAlleChats()
    {
        assertNotNull($this->client->buerger(REFRESH_TOKEN)->alleChats());
    }

    /**
     * Tests ->alleTermine()
     */
    public function testAlleTermine()
    {
        assertNotNull($this->client->buerger(REFRESH_TOKEN)->alleTermine());
    }

    /**
     * Tests ->chat()
     */
    public function testChat()
    {
        assertNotNull($this->client->buerger(REFRESH_TOKEN)->chat(TEST_CHAT_ID));
    }

    /**
     * Tests ->stelleAntrag()
     */
    public function testStelleAntrag()
    {
        assertNotEmpty($this->client->buerger(REFRESH_TOKEN)->stelleAntrag(
            TEST_ANTRAG_FORMULAR_ID,
            TEST_ANTRAG_WEITERLEITUNG_URL,
            TEST_ANTRAG_PARAMETER
        ));
    }

    /**
     * Tests ->daten()
     */
    public function testDaten()
    {
        assertNotEmpty($this->client->buerger(REFRESH_TOKEN)->daten());
    }
}