<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;

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
     * @param Category $category
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/category/{id}', name: 'categories_articles')]
    public function showCategory(Category $category, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['category' => $category], ['creationDate' => 'DESC']);

        return $this->render('category/list.html.twig', [
            'articles' => $articles,
            'category' => $category
        ]);
    }
}
