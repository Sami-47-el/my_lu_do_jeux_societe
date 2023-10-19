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
        foreach ($loans as $loan) {
            $dateReserve[] = [
                'date_start' => $loan->getDateStart(),
                'date_end' => $loan->getDateEnd(),
            ];
        }
        $form = $this->createForm(LoanType::class, $loan);
        $form-> handleRequest($request);
        $games = $em->getRepository(Game::class);
        $game = $games->find($id);
        $categories = $em->getRepository(Category::class);
        $category = $categories->findAll();
        
        if ($form->isSubmitted() && $form->isValid()){
            $loan = $form->getData();
            $loan->setUser($this->getUser());
            $loan->setGame($game);
            $em->persist($loan);
            $em->flush();
            return $this->redirectToRoute('app_game'); 
        }
        return $this->render('loan/index.html.twig', [
            'form' => $form,
            'categories' => $category,
            'dateReserve' => $dateReserve,
        ]);
    }
    public function validate($value, Constraint $constraint)
    {
        $dateStart = $value['date_start'];

        $
$dateEnd = $value['date_end'];

        if ($dateStart > $dateEnd){}
    }
}
