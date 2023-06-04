<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }
    /**
     * Save a team to the database.
     * 
     * @param Team $team The team entity to save.
     * @return void
     */

    public function save(Player $player): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($player);
        $entityManager->flush();
    }

    public function getPaginatedPlayers($page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        // Create a count query to retrieve the total number of players
        $countQuery = clone $queryBuilder;
        $countQuery->select('COUNT(p.id)');

        // Get the total count of players
        $totalPlayers= (int) $countQuery->getQuery()->getSingleScalarResult();

        // Create the main query for fetching paginated teams
        $queryBuilder->select('p')
            ->orderBy('p.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        // Execute the main query
        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        return [
            'paginator' => $paginator,
            'totalPlayers' => $totalPlayers,
        ];
    }
    /**
     * Find a player by ID
     *
     * @param int $id
     * @return Player|null
     */
    public function findPlayerById($id)
    {
        return $this->findOneBy(['id' => $id],['team' => 'ASC']);
    }
}