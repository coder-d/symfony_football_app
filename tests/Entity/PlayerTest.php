<?php

namespace App\Tests\Entity;

use App\Entity\Player;
use App\Entity\Team;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /**
     * @covers \App\Entity\Player::getName
     */
    public function testGetName(): void
    {
        $player = new Player();
        $player->setName('John');
        $this->assertEquals('John', $player->getName());
    }

    /**
     * @covers \App\Entity\Player::getSurName
     */
    public function testGetSurName(): void
    {
        $player = new Player();
        $player->setSurName('Doe');
        $this->assertEquals('Doe', $player->getSurName());
    }

    /**
     * @covers \App\Entity\Player::getTeam
     */
    public function testGetTeam(): void
    {
        $team = new Team();
        $player = new Player();
        $player->setTeam($team);
        $this->assertInstanceOf(Team::class, $player->getTeam());
    }
}