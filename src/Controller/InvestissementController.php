<?php

namespace App\Controller;

use App\Calcul\CalcBdd;
use App\Entity\Investissement;
use App\Entity\User;
use App\Form\InvestissementDeleteType;
use App\Form\InvestissementEditType;
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

            $this->addFlash('success','invest add');
            return $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);

        }

        return $this->render('investissement/create.html.twig', [
            'investissementForm' => $investForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(int $id, InvestissementRepository $investissementRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $invest = $investissementRepository->find($id);

        $investEditForm = $this->createForm(InvestissementEditType::class, $invest);
        $investEditForm->handleRequest($request);

        if ($investEditForm->isSubmitted() && $investEditForm->isValid()){

            //$investissementRepository->update($invest);

            $entityManager->persist($invest);
            $entityManager->flush();

            $this->addFlash('success','invest edit');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('investissement/edit.html.twig', [
            "investissement" => $invest,
            "investEditForm" => $investEditForm->createView()
        ]);
    }


    /**
     * @Route("/{id}", name="view")
     */
    public function view(int $id, InvestissementRepository $investissementRepository, RowRepository $rowRepository): Response
    {
        $invest = $investissementRepository->find($id);
        $rows = $rowRepository->findAllByInvestId($invest);


        return $this->render('investissement/view.html.twig', [
            "investissement" => $invest,
            "rows" => $rows
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(int $id, InvestissementRepository $investissementRepository, Request $request, EntityManagerInterface $entityManager, RowRepository $rowRepository): Response
    {
        $invest = $investissementRepository->find($id);


        $rows = $rowRepository->findAllByInvestId($invest->getId());


        $investDeleteForm = $this->createForm(InvestissementDeleteType::class, $invest);
        $investDeleteForm->handleRequest($request);


        if ($investDeleteForm->isSubmitted()){

            //delete row from this invest (cause of FK KEY)
            foreach ($rows as $row){
                $entityManager->remove($row);
                $entityManager->flush();
            }

            //delete invest from db
            $entityManager->remove($invest);
            $entityManager->flush();

            $this->addFlash('success','invest delete');
            return $this->redirectToRoute('main_home');
        }



        return $this->render('investissement/delete.html.twig', [
            "investDeleteForm" => $investDeleteForm->createView(),
            "investissement" => $invest
        ]);
    }
}
