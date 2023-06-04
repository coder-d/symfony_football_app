<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/teams", name="team_index", methods={"GET"})
     */
    public function index(Request $request, TeamRepository $teamRepository): Response
    {

        $page = $this->sanitizeUrlData((int) $request->query->getInt('page'));
        $page = $page?$page:(int)1;
        $limit = (int) $request->query->getInt('limit', 10);

        $paginationData = $teamRepository->getPaginatedTeams($page, $limit);
        $paginator = $paginationData['paginator'];
        $totalTeams = $paginationData['totalItems'];
        $teams = iterator_to_array($paginator);
        $maxPages = ceil($totalTeams / $limit);

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'maxPages' => $maxPages,
            'currentPage' => $page,
            'totalTeams' => $totalTeams
        ]);
    }
     /**
     * @Route("/teams/new", name="team_new", methods={"GET","POST"})
     */
    public function new(Request $request, TeamRepository $teamRepository): Response
    {
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the team using the repository's save function
            $teamRepository->save($team);

            return $this->redirectToRoute('team_index', ['id' => $team->getId()]);
        }
        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/teams/{id}", name="team_show", methods={"GET"})
     */
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    /**
     * @Route("/teams/{id}/edit", name="team_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('team_show', ['id' => $team->getId()]);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/teams/{id}", name="team_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('team_index');
    }

     /**
     * Sanitize URL data by escaping HTML special characters
     *
     * @param string $data The URL data to sanitize
     * @return string The sanitized URL data
     */
    private function sanitizeUrlData(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}