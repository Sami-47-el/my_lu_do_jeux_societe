<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
// use Symfony\Runtime\Symfony\Component\HttpFoundation\RequestRuntime;

class RegistrationController extends AbstractController
{
    // #[Route('/registration', name: 'app_registration')]
    // public function index( EntityManagerInterface $em): Response
    // {
    //     $categories = $em->getRepository(Category::class);
    //     $category = $categories->findAll();
    //     return $this->render('registration/index.html.twig', [
    //         'controller_name' => 'RegistrationController',
    //         'categories' => $category,

    //     ]);
    // }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherinterface $userPasswordHasher, EntityManagerInterface $em): Response{
        $user= new User();
        $categories = $em->getRepository(Category::class);
        $category = $categories->findAll();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm'=>$form->createView(),
            'categories' => $category,

        ]);

    }

}
