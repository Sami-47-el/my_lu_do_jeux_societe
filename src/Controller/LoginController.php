<?php

namespace App\Controller;
use App\Entity\Category;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $AuthenticationUtils , EntityManagerInterface $em): Response
    {
        $error= $AuthenticationUtils->getLastAuthenticationError();
        $lastusername = $AuthenticationUtils->getLastUserName();
        $categories = $em->getRepository(Category::class);
        $category = $categories->findAll();
        return $this->render('login/login.html.twig', [
            'last_username' => $lastusername,
            'error' =>$error,
            'categories' => $category,

        ]);
    }


}
