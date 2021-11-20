<?php

namespace App\Controller;

use App\Form\CategoryFormType;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @param CategoryRepository $repo
     * @return Response
     */
    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    #[Route('/category/new', name: 'create_category')]
    public function create(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('categories_articles', [
                'id' => $category->getId()
            ]);
        }

        return $this->renderForm('category/new.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @param Category $category
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/category/{id}', name: 'categories_articles')]
    public function showCategory(Category $category, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['category' => $category], ['creationDate' => 'DESC']);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'category' => $category,
            'title' => "Les articles concernant {$category->getName()}"
        ]);
    }


}
