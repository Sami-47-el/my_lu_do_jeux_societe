<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

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

    public function addGame(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form-> handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $brochureFile */
                $pictureFile = $form->get('picture')->getData();

                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($pictureFile) {
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();
    
                    // Move the file to the directory where brochures are stored
                    try {
                        $pictureFile->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        echo "image echoué";
                    }
    
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    
                    $game = $form->getData();
                    $game->setPicture($newFilename);
                    $game->setUser($this->getUser());
                    $em->persist($game);
                    $em->flush();
                    return $this->redirectToRoute('app_game');
                }
            }
            return $this->render('game/new.html.twig', [
                'form' => $form
            ]);   
   }

   #[Route('/game/{id}', name:'app_game_show')]
    public function show(EntityManagerInterface $em, int $id) : Response
    {
        $games = $em->getRepository(Game::class);
        $game = $games->find($id);
        return $this->render('game/show.html.twig', [
            'game' => $game,

        ]);
    }

    #[Route('/game/edit/{id}', name:'app_game_edit')]
    public function update(Request $request, EntityManagerInterface $em,  int $id , SluggerInterface $slugger): Response
    {
        $games = $em->getRepository(Game::class) ;
        $game = $games->find($id);
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $brochureFile */
             $pictureFile = $form->get('picture')->getData();

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
            if ($pictureFile) {
                 $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 try {
                    
                     $pictureFile->move(
                         $this->getParameter('brochures_directory'),
                         $newFilename
                     );
                     $game->setPicture($newFilename);

                 } catch (FileException $e) {
                     echo "image echoué";
                 }
 
                 // updates the 'brochureFilename' property to store the PDF file name
                 // instead of its contents
                
            }
            
            $em->flush();
            return $this->redirectToRoute('app_game');
        }
        return $this->render('game/edit.html.twig', [
            'form' => $form
        ]);   
   }

   #[Route('game/delete/{id}', name: 'app_game_delete')]
   public function delete(EntityManagerInterface $em, int $id): Response
   {
    $games = $em->getRepository(Game::class);
    $game = $games->find($id);
    $file = 'Uploads/' . $game->getPicture();
   if( file_exists($file)){
        unlink($file);
    }
    $em->remove($game);
    $em->flush();
    
    return $this->redirectToRoute('app_game');
   }
} 
