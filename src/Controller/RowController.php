<?php

namespace App\Controller;

use App\Entity\Investissement;
use App\Entity\Row;
use App\Form\RowDeleteType;
use App\Form\RowType;
use App\Form\RowUpdateType;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("investissement/{id}/row", name="row_")
 */
class RowController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(): Response
    {
        return $this->render('row/index.html.twig', [
            'controller_name' => 'RowController',
        ]);
    }

    /**
     * @Route("/{rowId}/update", name="update")
     */
    public function update(int $id, int $rowId, Request $request, EntityManagerInterface $entityManager, InvestissementRepository $investissementRepository, RowRepository $rowRepository): Response
    {
        $invest = new Investissement();
        $invest = $investissementRepository->find($id);


        $row = new Row();
        $row = $rowRepository->find($rowId);

        $lastTotalRowValue = $row->getTotalValue();

        $rowUpdateForm = $this->createForm(RowUpdateType::class, $row);
        $rowUpdateForm->handleRequest($request);

        if ($rowUpdateForm->isSubmitted() && $rowUpdateForm->isValid()){
            $entityManager->persist($row);
            $entityManager->flush();

            $newTotalRowValue = $row->getTotalValue();

            $difference = $newTotalRowValue - $lastTotalRowValue;


            $newTotalInvestValue = $invest->getTotalValue() + $difference;

            $invest->setLastModif(new \DateTime());
            $invest->setTotalValue($newTotalInvestValue);
            $entityManager->persist($invest);
            $entityManager->flush();

            $this->addFlash('success','row update');
            return  $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);
        }


        return $this->render('row/update.html.twig', [
            'investissements' => $invest,
            'row' => $row,
            'rowUpdateForm' => $rowUpdateForm->createView()
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(int $id, Request $request, EntityManagerInterface $entityManager, InvestissementRepository $investissementRepository): Response
    {

        $invest = $investissementRepository->find($id);


        $row = new Row();
        $row->setInvestAttach($invest);

        $rowForm = $this->createForm(RowType::class, $row);
        $rowForm->handleRequest($request);

        if ($rowForm->isSubmitted() && $rowForm->isValid()){
            //add row in db
            $entityManager->persist($row);
            $entityManager->flush();


            $invest->setLastModif(new \DateTime());
            //update price in db
            $newTotalInvestValue = $invest->getTotalValue() + $row->getTotalValue();
            $invest->setTotalValue($newTotalInvestValue);
            $entityManager->persist($invest);
            $entityManager->flush();

            $this->addFlash('success', 'row add');
            return  $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);

        }


        return $this->render('/row/create.html.twig', [
            'rowForm' => $rowForm->createView(),
            "investissements" => $invest
        ]);
    }

    /**
     * @Route("/{rowId}/delete", name="delete")
     */
    public function delete(int $id, int $rowId, Request $request, EntityManagerInterface $entityManager, InvestissementRepository $investissementRepository, RowRepository $rowRepository): Response
    {
        $invest = new Investissement();
        $invest = $investissementRepository->find($id);

        $row = new Row();
        $row = $rowRepository->find($rowId);

        $rowDeleteForm = $this->createForm(RowDeleteType::class, $row);
        $rowDeleteForm->handleRequest($request);

        if ($rowDeleteForm->isSubmitted()){
            //delete row from db
            $entityManager->remove($row);
            $entityManager->flush();

            //update price in db
            $newTotalInvestValue = $invest->getTotalValue() - $row->getTotalValue();
            $invest->setTotalValue($newTotalInvestValue);
            $entityManager->persist($invest);
            $entityManager->flush();

            $this->addFlash('success','row delete');
            return $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);
        }

        return $this->render('row/delete.html.twig', [
            'rowDeleteForm' => $rowDeleteForm->createView(),
            'row' => $row,
            'investissement' => $invest
        ]);
    }
}
