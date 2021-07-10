<?php

namespace App\Controller;

use App\Repository\InvestissementRepository;
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
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(int $id, InvestissementRepository $investissementRepository): Response
    {
        $invest = $investissementRepository->find($id);

        return $this->render('investissement/edit.html.twig', [
            "investissement" => $invest
        ]);
    }


    /**
     * @Route("/{id}", name="view")
     */
    public function view(int $id, InvestissementRepository $investissementRepository): Response
    {
        $invest = $investissementRepository->find($id);

        return $this->render('investissement/view.html.twig', [
            "investissement" => $invest
        ]);
    }
}
