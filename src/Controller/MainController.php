<?php

namespace App\Controller;

use App\Repository\InvestissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(InvestissementRepository $investissementRepository): Response{

        $user = $this->getUser();

        $investissements = $investissementRepository->findAllByUserId($user);


        return $this->render('main/home.html.twig', [
            "investissements" => $investissements
        ]);
    }
}
