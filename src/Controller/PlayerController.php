<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlayerController extends AbstractController
{
    private $playerRepository;
    private $teamRepository;

    public function __construct(PlayerRepository $playerRepository, TeamRepository $teamRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @Route("/player", name="player_index")
     */
    public function index(Request $request): Response
    {
        $page = $this->sanitizeUrlData((int) $request->query->getInt('page'));
        $page = $page?$page:(int)1;
        $limit = (int) $request->query->getInt('limit', 10);

        $paginationData = $this->playerRepository->getPaginatedPlayers($page, $limit);
        $paginator = $paginationData['paginator'];
        $totalPlayers = $paginationData['totalPlayers'];
        $players = iterator_to_array($paginator);
        $maxPages = ceil($totalPlayers / $limit);
        $teams = $this->teamRepository->findAll();

        return $this->render('player/index.html.twig', [
            'teams' => $teams,
            'maxPages' => $maxPages,
            'currentPage' => $page,
            'totalPlayers' => $totalPlayers,
            'players' => $players
        ]);
    }

    /**
     * @Route("/player/add", name="player_add")
     */
    public function new(Request $request): Response
    {
        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player, [
            'team_choices' => $this->teamRepository->findAll(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the plater using the repository's save function
            $this->playerRepository->save($player);

            return $this->redirectToRoute('player_index', ['id' => $player->getId()]);
        }

        return $this->render('player/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/player/sell/{id}/{sellerId}/{buyerId}/{amount}", name="sell_player")
     */

    public function sellPlayer(Request $request): Response
    {
        // int $player, int $sellerTeam, int $buyerTeam, int $amount
        //dd($request->query->getInt('id'));
        $playerId = $request->query->getInt('id');
        $sellerTeamId = $request->query->getInt('sellerId');
        $buyerTeamId  = $request->query->getInt('buyerId');
        $amount = $request->query->getInt('amount');
        // Check if the seller team owns the player
        $player= $this->playerRepository->findPlayerById($playerId);
        if($player)
        {
            $playerTeam = $player->getTeam();
        }
        else {
            $playerTeam = null;
        }
        if ($playerTeam !== null && $playerTeam->getId() !== $sellerTeamId) {
            $message = "Player does not belong to the seller team";
            $response = new JsonResponse([
                'success' => false,
                'message' => $message,
            ]);
            return $response;
        }

        // Check if the buyer team has enough money balance
        $buyerTeam = $this->teamRepository->findTeamByID($buyerTeamId);
        if ($buyerTeam !== null && $buyerTeam->getMoneyBalance() < $amount || $playerTeam->getId() == $buyerTeamId) {
            $message = "Buyer team does not have enough money balance or buyer team and seller team are same";
            $response = new JsonResponse([
                'success' => false,
                'message' => $message,
            ]);
            return $response;
        }

        // Update the player's team and deduct the amount from the buyer team's money balance
        $player->setTeamId($buyerTeamId);
        $this->playerRepository->save($player);
        
        $buyerTeamBalance = $buyerTeam->getMoneyBalance() - $amount;
        $buyerTeam->setMoneyBalance($buyerTeamBalance);
        $this->teamRepository->save($buyerTeam);

        $sellerTeamBalance = $playerTeam->getMoneyBalance() + $amount;
        $playerTeam->setMoneyBalance($sellerTeamBalance);
        $this->teamRepository->save($playerTeam);

        // Retrieve or generate  dataset
        $data = [
            'buyerTeamBalance' => $buyerTeamBalance,
            'sellerTeamBalance' => $sellerTeamBalance
        ];

        // Create the JSON response
        $response = new JsonResponse([
            'success' => true,
            'message' => 'Player sold successfully',
            'data' => $data,
        ]);
        return $response;
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