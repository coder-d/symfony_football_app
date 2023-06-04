<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\Player;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create some countries
        $country1 = new Country();
        $country1->setName('England');
        $manager->persist($country1);

        $country2 = new Country();
        $country2->setName('Scotland');
        $manager->persist($country2);

        // Create some teams with players
        for ($i = 1; $i <= 5; $i++) {
            $team = new Team();
            $team->setName('Team ' . $i);
            $team->setCountry($i % 2 == 0 ? $country2 : $country1);
            $team->setMoneyBalance(1000.00 * $i);

            $manager->persist($team);

            // Create players for each team
            for ($j = 1; $j <= 3; $j++) {
                $player = new Player();
                $player->setName('Player ' . $j);
                $player->setSurname('Surname ' . $j);
                $player->setTeam($team);

                $manager->persist($player);
            }
        }

        $manager->flush();
    }
}