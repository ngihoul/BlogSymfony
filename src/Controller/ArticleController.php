<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Entity\Article;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
    private string $title = 'Tous les articles';

    /**
     * @param ArticleRepository $repo
     * @return Response
     */
    #[Route("/article", name: "article")]
    public function index(ArticleRepository $repo, Request $request): Response
    {
        $getParam = $request->query->get('q');
        $articles = is_null($getParam) ?
            $repo->findAll() :
            $repo->findTitleOrContentWith($getParam);

        return $this->render('article/index.html.twig', [
            'title' => $this->title,
            'q' => $getParam,
            'articles' => $articles
        ]);
    }

    /**
     * @return Response
     */
    #[Route("/article/twig", name: "twig")]
    public function twig(): Response
    {
        // Create array of random numbers
        $arrayNumbers = range(1, 100);
        shuffle($arrayNumbers);
        $arrayNumbers = array_slice($arrayNumbers, 0, 10);

        // Create array of strings
        $arrayString = ['hello', 'bonjour', 'hallo', 'hoi'];
        shuffle($arrayString);

        // Create random date
        $date = $this->randomDate('2021-01-01', '2021-12-31');

        return $this->render('article/array.html.twig', [
            'title' => $this->title,
            'arrayNumbers' => $arrayNumbers,
            'arrayString' => $arrayString,
            'date' => $date
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     */
    #[Route('/article/new', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_detail', ['id' => $article->getId()]);
        }

        return $this->renderForm('article/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/article/update/', name: 'update_article')]
    public function update(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        
    }

    /**
     * @param int $id
     * @param ArticleRepository $repo
     * @return Response
     */
    #[Route("/article/{id}", name: "article_detail")]
    public function show($id, ArticleRepository $repo): Response
    {
        if (!is_numeric($id)) {
            return $this->render('article/404.html.twig');
        }

        $article = $repo->find($id);

        if (!$article) {
            return $this->render('article/404.html.twig');
        }

        return $this->render('article/article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @param string $year
     * @param ArticleRepository $repo
     * @return Response
     */
    #[Route("/article/year/{year}", name: "year_articles")]
    public function showArticlesByYear(string $year, ArticleRepository $repo): Response
    {
        $articles = $repo->getArticlesByYear($year);

        if (!$articles) {
            return $this->render('article/404.html.twig');
        }

        return $this->render('article/index.html.twig', [
            'title' => "Tous les articles publiés en $year",
            'articles' => $articles
        ]);
    }

    /**
     * @param Article $article
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route("/article/{id}/up", name: "up_vote")]
    public function upVote(Article $article, EntityManagerInterface $em): JsonResponse
    {
        $article->upVote();

        $em->flush();

        return new JsonResponse($article->getVoteCounter());
    }

    /**
     * @param Article $article
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route("/article/{id}/down", name: "down_vote")]
    public function downVote(Article $article, EntityManagerInterface $em): JsonResponse
    {
        $article->downVote();

        $em->flush();

        return new JsonResponse($article->getVoteCounter());
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return string
     */
    public function randomDate($startDate, $endDate): string
    {
        $min = strtotime($startDate);
        $max = strtotime($endDate);

        $date = rand($min, $max);

        return date('d/m/Y', $date);
    }
}
