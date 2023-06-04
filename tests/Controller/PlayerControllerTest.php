<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\TeamController;
use Symfony\Bridge\Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\NullHandler;

class PlayerControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/player');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Players');
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->getBaseUrl().'/player/add');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        // You can continue with more assertions based on your specific form structure
    }
    private function getBaseUrl(): string
    {
        return $_ENV['BASE_URL'];
    }
}