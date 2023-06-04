<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * Retrieves a paginated list of teams.
     *
     * @param int $page The page number (default: 1)
     * @param int $limit The number of items per page (default: 10)
     * @return array An array containing the Paginator object and the total item count
    */
    public function getPaginatedTeams($page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        // Create a count query to retrieve the total number of teams
        $countQuery = clone $queryBuilder;
        $countQuery->select('COUNT(t.id)');

        // Get the total count of teams
        $totalItems = (int) $countQuery->getQuery()->getSingleScalarResult();

        // Create the main query for fetching paginated teams
        $queryBuilder->select('t')
            ->orderBy('t.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        // Execute the main query
        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        return [
            'paginator' => $paginator,
            'totalItems' => $totalItems,
        ];
    }

    
    /**
     * Find a team by ID
     *
     * @param int $id
     * @return Team|null
     */
    public function findTeamById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Save a team
     *
     * @param Team $team
     */
    public function save(Team $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    /**
     * Delete a team
     *
     * @param Team $team
     */
    public function delete(Team $team)
    {
        $this->_em->remove($team);
        $this->_em->flush();
    }
}