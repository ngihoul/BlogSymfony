<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Entity\Article;

class ArticleController extends AbstractController
{
    public $title = 'Tous les articles';

     #[Route("/article", name: "article")]
    public function index(EntityManagerInterface $em): Response
    {

        $repository = $em->getRepository(Article::class);
        $articles = $repository->findAll();

        return $this->render('article/index.html.twig', [
            'title' => $this->title,
            'articles' => $articles
        ]);
    }

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

    #[Route("/article/{id}", name: "article_detail")]
    public function show($id, EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        $article = $repository->find($id);

        if(!$article) {
            return $this->render('article/404.html.twig');
        }

        return $this->render('article/article.html.twig', [
            'article' => $article
        ]);
    }

    #[Route("/article/year/{year}", name:"year_articles")]
    public function showArticlesByYear($year, entityManagerInterface $em) {
        $repository = $em->getRepository(Article::class);
        $articles = $repository->getArticlesByYear($year);

        if(!$articles) {
            return $this->render('article/404.html.twig');
        }

        return $this->render('article/index.html.twig', [
            'title' => "Tous les articles publiés en $year",
            'articles' => $articles
        ]);
    }

// Voting actions

    #[Route("/article/{id}/up", name: "up_vote")]
    public function upVote($id, EntityManagerInterface $em) {

        $repository = $em->getRepository(Article::class);
        $article = $repository->find($id);
        $article->upVote();
        $em->persist($article);
        $em->flush();

        return new JsonResponse($article->getVoteCounter());
    }

    #[Route("/article/{id}/down", name: "down_vote")]
    public function downVote($id, EntityManagerInterface $em) {
        $repository = $em->getRepository(Article::class);
        $article = $repository->find($id);
        $article->downVote();
        $em->persist($article);
        $em->flush();

        return new JsonResponse($article->getVoteCounter());
    }

// Create new article

    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $em) {
        $faker = Factory::create();
        $article = new Article();
        $article->setTitle($faker->realText(50, 1));
        $article->setContent($faker->realText(1500, 2));
        $article->setCreationDate(new \DateTime("2015-04-24"));
    
        $em->persist($article);
        $em->flush();

        return $this->render('home/index.html.twig', [
            'title' => $this->title
        ]);
    }

    // Magical articles

    #[Route('/magical', name: 'magical')]
    public function magical(EntityManagerInterface $em) {
        $repository = $em->getRepository(Article::class);
        $articles = $repository->findContentWith('magical');

        return $this->render('article/magical.html.twig', [
            'title' => "Articles avec le mot 'magical'",
            'articles' => $articles
        ]);
    }



    public function randomDate($startDate, $endDate)
    {
        $min = strtotime($startDate);
        $max = strtotime($endDate);

        $date = rand($min, $max);

        return date('d/m/Y', $date);
    }
}
