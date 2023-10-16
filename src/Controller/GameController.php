<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(EntityManagerInterface $em): Response
    {
        $games = $em->getRepository(Game::class);
        $game = $games->findAll();

        return $this->render('game/index.html.twig', [
            'games' => $game,
        ]);
    }

    #[Route('/game/new', name: 'app_game_new')]

    public function addGame(Request $request, EntityManagerInterface $em): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();
            $game->setUser($this->getUser());
            $em->persist($game);
            $em->flush();
        }

        return $this->render('game/new.html.twig', [
            'form' => $form
        ]);
        
    }
}
