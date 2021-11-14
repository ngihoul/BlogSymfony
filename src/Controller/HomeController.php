<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private string $title = "Super blog";

    /**
     * @param ArticleRepository $repo
     * @return Response
     */
    #[Route("/", name: "home")]
    public function home(ArticleRepository $repo): Response
    {
        $yearsOfArticles = $repo->getYearsOfArticles();

        return $this->render('home/index.html.twig', [
            'title' => $this->title,
            'yearsOfArticles' => $yearsOfArticles
        ]);
    }

    /**
     * Controller's method to create a list of years in aside
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function getYearsOfArticles(ArticleRepository $articleRepository): Response
    {
        $years = $articleRepository->getYearsOfArticles();

        // Using fragments template (/_fragment)
        return $this->render("home/_years.html.twig", [
            'yearsOfArticles' => $years
        ]);
    }

    public function getCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('home/_categories.html.twig', [
            'categories' => $categories
        ]);
    }
}
