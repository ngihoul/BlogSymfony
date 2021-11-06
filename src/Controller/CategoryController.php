<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Category;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Category::class);
        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
