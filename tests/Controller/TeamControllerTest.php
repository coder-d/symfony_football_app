<?php

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\TeamController;
use Symfony\Bridge\Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\NullHandler;
use ReflectionClass;

class TeamControllerTest extends WebTestCase
{
    private $logger;

    protected function setUp(): void
    {
        parent::setUp();
        
    }
    public function testIndex(): void
    {   
        $client = static::createClient();

        $crawler = $client->request('GET', $this->getBaseUrl().'/teams');

        $this->assertEquals(200, (int) $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Team List');
    }


    public function testNew(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->getBaseUrl().'/teams/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Create New Team');
    }

    private function getBaseUrl(): string
    {
        return $_ENV['BASE_URL'];
    }
}