<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods:['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name:'app_category_new', methods:['GET', 'POST'])]
    public function addCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $categories = $em->getRepository(Category::class)->findAll();
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->render('category/new.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'form' => $form,
        ]);

    }
    #[Route('/{id}', name: 'app_category_show', methods:['GET', 'POST'])]
    public function showCategory(category $category, EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_category_edit', methods:['GET', 'POST'])]
    public function editCategory(Request $request, category $category, EntityManagerInterface $em, int $id): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $categories = $em->getRepository(Category::class)->findAll();
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this ->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_category_delete', methods:['POST'])]
    public function deleteCategory(Request $request, category $category, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        
    }
}
