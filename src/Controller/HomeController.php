<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route("/", name: "home")]
    public function home(Request $request, ArticleRepository $articleRepository): Response
    {

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $articleRepository->getArticlePaginator($offset);

        return $this->render('home/index.html.twig', [
            'articles' => $paginator,
            'previous' => $offset - ArticleRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ArticleRepository::PAGINATOR_PER_PAGE)
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
