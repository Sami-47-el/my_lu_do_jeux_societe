<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Entity\Loan;
use App\Entity\Game;
use App\Form\LoanType;
use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoanController extends AbstractController
{
    // #[Route('/loan', name: 'app_loan')]
    // public function index(EntityManagerInterface $em): Response
    // {
    //     $categories = $em->getRepository(Category::class);
    //     $category = $categories->findAll();
    //     return $this->render('loan/index.html.twig', [
    //         'controller_name' => 'LoanController',
    //         'categories' => $category,
    //     ]);
    // }

    #[Route('/loan/{id}', name: 'app_loan')]
public function loannig(Request $request, EntityManagerInterface $em, LoanRepository $loanRepository , INT $id): Response
{
    $loan = new Loan();
    $loans = $loanRepository->findAll();
    $dateReserve = [];

    foreach ($loans as $existingLoan) {
        $dateReserve[] = [
            'date_start' => $existingLoan->getDateStart(),
            'date_end' => $existingLoan->getDateEnd(),
        ];
    }

    $form = $this->createForm(LoanType::class, $loan);
    $form->handleRequest($request);

    $games = $em->getRepository(Game::class);
    $game = $games->find($id);
    $categories = $em->getRepository(Category::class);
    $category = $categories->findAll();

    if ($form->isSubmitted() && $form->isValid()) {
        $loanData = $form->getData();
        $loanData->setUser($this->getUser());
        $loanData->setGame($game);

        // Vérifier la disponibilité du jeu en comparant les dates
        $canReserve = true;

        foreach ($dateReserve as $date) {
            $startDate = $date['date_start'];
            $endDate = $date['date_end'];

            if ($loanData->getDateStart() >= $startDate && $loanData->getDateStart() <= $endDate) {
                $canReserve = false;
                break;
            }

            if ($loanData->getDateEnd() >= $startDate && $loanData->getDateEnd() <= $endDate) {
                $canReserve = false;
                break;
            }
        }

        if ($canReserve) {
            $em->persist($loanData);
            $em->flush();
            return $this->redirectToRoute('app_game');
        } else {
            // Informer l'utilisateur que le jeu n'est pas disponible pour les dates spécifiées
            $this->addFlash('warning', 'Le jeu n\'est pas disponible pour ces dates.');
            return $this->redirectToRoute('app_game_show',['id'=>$id]);
        }
    }

    return $this->render('loan/index.html.twig', [
        'form' => $form->createView(),
        'categories' => $category,
        'dateReserve' => $dateReserve,
    ]);
}
   
}
