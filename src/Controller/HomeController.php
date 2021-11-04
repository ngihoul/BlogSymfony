<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class HomeController extends AbstractController
{

    public $title = "Super blog";

    #[Route("/", name: "home")]
    public function home(EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Article::class);
        $yearsOfArticles = $repository->getYearsOfArticles();

        return $this->render('home/index.html.twig', [
            'title' => $this->title,
            'yearsOfArticles' => $yearsOfArticles
        ]);
    }
}
