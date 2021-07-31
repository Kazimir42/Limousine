<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/stats", name="stats_")
 */
class StatistiquesController extends AbstractController
{

    /**
     * @Route("", name="home")
     */
    public function index(): Response
    {
        return $this->render('statistiques/index.html.twig', [

        ]);
    }
}