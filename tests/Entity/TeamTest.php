<?php

namespace App\Tests\Entity;

use App\Entity\Team;
use App\Entity\Player;
use App\Entity\Country;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testSetName(): void
    {
        $team = new Team();
        $name = 'Test Teams';

        $team->setName($name);

        $this->assertEquals($name, $team->getName());
    }

    /**
     * @covers \App\Entity\Team::setName
     */
    public function testSetNameWithAnnotation(): void
    {
        $team = new Team();
        $name = 'Test Team';

        $team->setName($name);

        $this->assertEquals($name, $team->getName());
    }
    /**
     * @covers \App\Entity\Team::getName
     * @covers \App\Entity\Team::setName
     */
    public function testGetNameAndSetName(): void
    {
        $team = new Team();
        $name = 'Test Team';

        $team->setName($name);

        $this->assertEquals($name, $team->getName());
    }

    /**
     * @covers \App\Entity\Team::getCountryId
     * @covers \App\Entity\Team::setCountryId
     */
    public function testGetCountryIdAndSetCountryId(): void
    {
        $team = new Team();
        $countryId = 123;

        $team->setCountryId($countryId);

        $this->assertEquals($countryId, $team->getCountryId());
    }

    /**
     * @covers \App\Entity\Team::getCountry
     * @covers \App\Entity\Team::setCountry
     */
    public function testGetCountryAndSetCountry(): void
    {
        $team = new Team();
        $country = new Country();

        $team->setCountry($country);

        $this->assertEquals($country, $team->getCountry());
    }

    /**
     * @covers \App\Entity\Team::getMoneyBalance
     * @covers \App\Entity\Team::setMoneyBalance
     */
    public function testGetMoneyBalanceAndSetMoneyBalance(): void
    {
        $team = new Team();
        $moneyBalance = 100.50;

        $team->setMoneyBalance($moneyBalance);

        $this->assertEquals($moneyBalance, $team->getMoneyBalance());
    }

    /**
     * @covers \App\Entity\Team::getPlayers
     * @covers \App\Entity\Team::addPlayer
     * @covers \App\Entity\Team::removePlayer
     */
    public function testGetPlayersAndManagePlayers(): void
    {
        $team = new Team();
        $player1 = new Player();
        $player2 = new Player();

        $team->addPlayer($player1);
        $team->addPlayer($player2);

        $players = $team->getPlayers();
        $this->assertCount(2, $players);
        $this->assertTrue($players->contains($player1));
        $this->assertTrue($players->contains($player2));

        $team->removePlayer($player1);
        $players = $team->getPlayers();
        $this->assertCount(1, $players);
        $this->assertFalse($players->contains($player1));
        $this->assertTrue($players->contains($player2));
    }

}

