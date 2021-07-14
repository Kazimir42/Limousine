<?php

namespace App\Controller;

use App\Calcul\CalcBdd;
use App\Entity\Investissement;
use App\Entity\User;
use App\Form\InvestissementType;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(InvestissementRepository $investissementRepository): Response
    {
        $user = $this->getUser();

        $investissements = $investissementRepository->findAllByUserId($user);

        return $this->render('investissement/home.html.twig', [
            "investissements" => $investissements
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();


        $invest = new Investissement();
        $invest->setDateCreated(new \DateTime());
        $invest->setLastModif(new \DateTime());
        $invest->setStatut(true);
        $invest->setUser($user);

        $investForm = $this->createForm(InvestissementType::class, $invest);

        $investForm->handleRequest($request);

        if ($investForm->isSubmitted() && $investForm->isValid()){

            $entityManager->persist($invest);
            $entityManager->flush();

            $this->addFlash('succes','invest add');
            return $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);

        }

        return $this->render('investissement/create.html.twig', [
            'investissementForm' => $investForm->createView()
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
    public function view(int $id, InvestissementRepository $investissementRepository, RowRepository $rowRepository, CalcBdd $calcBdd): Response
    {
        $invest = $investissementRepository->find($id);
        $rows = $rowRepository->findAllByInvestId($invest);


        //$total = $calcBdd->totalValue($rows);
        //$invest->setTotalValue($total);


        return $this->render('investissement/view.html.twig', [
            "investissement" => $invest,
            "rows" => $rows
        ]);
    }
}
