<?php

namespace OptiGov\Tests\Responsibilities;

use Curfle\Routing\Route;
use OptiGov\Client;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertNotEmpty;

class VerwaltungResponsibilityTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client(BACKEND_URL);
    }

    /**
     * Tests ->alleDienstleistungen()
     */
    public function testDienstleistungen()
    {
        assertNotEmpty($this->client->verwaltung(1)->alleDienstleistungen());
    }

    /**
     * Tests ->alleDienstleistungskategorien()
     */
    public function testAlleDienstleistungskategorien()
    {
        assertNotEmpty($this->client->verwaltung(1)->alleDienstleistungskategorien());
    }

    /**
     * Tests ->alleDienstleistungskategorien()
     */
    public function testAlleEinrichtungen()
    {
        assertNotEmpty($this->client->verwaltung(1)->alleEinrichtungen());
    }

    /**
     * Tests ->alleDienstleistungskategorien()
     */
    public function testAlleMitarbeiter()
    {
        assertNotEmpty($this->client->verwaltung(1)->alleMitarbeiter());
    }
}