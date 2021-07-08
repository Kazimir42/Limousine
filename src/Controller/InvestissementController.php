<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/investissement", name="investissement_")
 */
class InvestissementController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(): Response
    {
        return $this->render('investissement/home.html.twig', [
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(): Response
    {
        return $this->render('investissement/create.html.twig', [
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(): Response
    {
        return $this->render('investissement/edit.html.twig', [
        ]);
    }
}
