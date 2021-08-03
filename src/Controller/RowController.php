<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Entity\ETF;
use App\Entity\Investissement;
use App\Entity\Row;
use App\Entity\Stock;
use App\Form\RowDeleteType;
use App\Form\RowType;
use App\Form\RowUpdateType;
use App\Repository\CryptoRepository;
use App\Repository\CurrencyChangeRepository;
use App\Repository\ETFRepository;
use App\Repository\InvestissementRepository;
use App\Repository\RowRepository;
use App\Repository\StockRepository;
use App\Service\UpdateTotalAccountValue;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("investment/{id}/row", name="row_")
 */
class RowController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(): Response
    {
        return $this->render('row/stock.html.twig', [
            'controller_name' => 'RowController',
        ]);
    }

    /**
     * @Route("/{rowId}/update", name="update")
     */
    public function update(int $id, int $rowId, Request $request, EntityManagerInterface $entityManager, InvestissementRepository $investissementRepository, RowRepository $rowRepository, CurrencyChangeRepository $currencyChangeRepository, UpdateTotalAccountValue $totalAccountValue): Response
    {
        $invest = $investissementRepository->find($id);

        if ($this->getUser()->getId() == $invest->getUser()->getId()){

            $row = $rowRepository->find($rowId);

            $lastTotalRowValue = $row->getTotalValueUSD();

            $rowUpdateForm = $this->createForm(RowUpdateType::class, $row);
            $rowUpdateForm->handleRequest($request);

            if ($rowUpdateForm->isSubmitted() && $rowUpdateForm->isValid()){

                if ($row->getDevise() == 'EUR'){
                    $row->setValueUSD($row->getValue() *  $currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue());
                    $row->setTotalValueUSD($row->getValueUSD() * $row->getNumber());
                }else{
                    $row->setValueUSD($row->getValue());
                    $row->setTotalValueUSD($row->getTotalValue());
                }

                $entityManager->persist($row);
                $entityManager->flush();

                //update price in db
                $totalAccountValue->updateTotalValue();

                $this->addFlash('success','row update');
                return  $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);
            }


            return $this->render('row/update.html.twig', [
                'investissements' => $invest,
                'row' => $row,
                'rowUpdateForm' => $rowUpdateForm->createView()
            ]);

        }else{
            throw $this->createAccessDeniedException();
        }

    }

    /**
     * @Route("/create", name="create")
     */
    public function create(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        InvestissementRepository $investissementRepository,
        CurrencyChangeRepository $currencyChangeRepository,
        StockRepository $stockRepository,
        ETFRepository $ETFRepository,
        UpdateTotalAccountValue $totalAccountValue,
        CryptoRepository $cryptoRepository
    ): Response
    {
        $invest = $investissementRepository->find($id);

        $row = new Row();
        $row->setInvestAttach($invest);

        $rowForm = $this->createForm(RowType::class, $row);
        $rowForm->handleRequest($request);

        if ($rowForm->isSubmitted() && $rowForm->isValid()){

            $resultName = $rowForm->get('resultName')->getData();

            if ($row->getDevise() == 'EUR'){
                $theValue = $row->getValue() *  $currencyChangeRepository->findOneBy(array('currencyFrom' => 'EUR', 'currencyTo' => 'USD'))->getRateValue();
                $row->setValueUSD(round($theValue, 2));
                $row->setTotalValueUSD(round($row->getValueUSD() * $row->getNumber(), 2));
            }else{
                $row->setValueUSD($row->getValue());
                $row->setTotalValueUSD($row->getTotalValue());
            }

            //add row in db
            $entityManager->persist($row);
            $entityManager->flush();


            $invest->setLastModif(new \DateTime());


            //update total invest price in db
            $totalAccountValue->updateTotalValue();




            //create or update the stock/etf or crypto in a specifiq table in db
            switch ($row->getType()){
                case "STOCK":

                    $stock = new Stock();
                    $stock->setName($resultName);
                    $stock->setSymbol($row->getSymbol());
                    $stock->setValue($row->getValue());
                    $stock->setDevise($row->getDevise());
                    $stock->setUpdatedAt(new \DateTime());

                    //GET THE STOCK
                    $stockExist = $stockRepository->findOneBy(array('symbol' => $row->getSymbol()));

                    //CHECK IF STOCK ALREADY EXSITE
                    if (is_null($stockExist)){
                        //CREATE HIM
                        $entityManager->persist($stock);
                        $entityManager->flush();
                    }else{
                        //CHECK IF THE VALUE IS ALREADY UPDATE IN DB
                        if($stockExist->getValue() == $row->getValue()){
                            //DO NOTHING
                        }else{
                            //UPDATE THE STOCK WITH NEW VALUE
                            $stockRepository->updateStockBySymbol($stock);
                        }
                    }
                    break;
                case "ETF":

                    $etf = new ETF();
                    $etf->setName($resultName);
                    $etf->setSymbol($row->getSymbol());
                    $etf->setValue($row->getValue());
                    $etf->setDevise($row->getDevise());
                    $etf->setUpdatedAt(new \DateTime());

                    //GET THE ETF
                    $etfExist = $ETFRepository->findOneBy(array('symbol' => $row->getSymbol()));

                    //CHECK IF ETG ALREADY EXSITE
                    if (is_null($etfExist)){
                        //CREATE HIM
                        $entityManager->persist($etf);
                        $entityManager->flush();
                    }else{
                        //CHECK IF THE VALUE IS ALREADY UPDATE IN DB
                        if($etfExist->getValue() == $row->getValue()){
                            //DO NOTHING
                        }else{
                            //UPDATE THE STOCK WITH NEW VALUE
                            $ETFRepository->updateETFBySymbol($etf);
                        }
                    }
                    break;
                case "CRYPTO":

                    $crypto = new Crypto();
                    $crypto->setName($resultName);
                    $crypto->setSymbol($row->getSymbol());
                    $crypto->setUpdatedAt(new \DateTime());

                    if ($row->getDevise() == "USD" || is_null($row->getDevise())){

                        $crypto->setValueUsd($row->getValue());
                        //UPDATE CRYPTO IN DB
                        $cryptoRepository->updateCryptoUSDBySymbol($crypto);

                    }elseif ($row->getDevise() == "EUR"){

                        $crypto->setValueEur($row->getValue());
                        //UPDATE CRYPTO IN DB
                        $cryptoRepository->updateCryptoEURBySymbol($crypto);

                    }

                    break;
                case "OTHER":
                    $entityManager->persist($row);
                    $entityManager->flush();
                    break;
            }





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
    public function delete(int $id, int $rowId, Request $request, EntityManagerInterface $entityManager, InvestissementRepository $investissementRepository, RowRepository $rowRepository, UpdateTotalAccountValue $totalAccountValue): Response
    {
        $invest = $investissementRepository->find($id);

        if ($this->getUser()->getId() == $invest->getUser()->getId()){

            $row = $rowRepository->find($rowId);

            $rowDeleteForm = $this->createForm(RowDeleteType::class, $row);
            $rowDeleteForm->handleRequest($request);

            if ($rowDeleteForm->isSubmitted()){


                //delete row from db
                $entityManager->remove($row);
                $entityManager->flush();

                //update price in db
                $totalAccountValue->updateTotalValue();

                $this->addFlash('success','row delete');
                return $this->redirectToRoute('investissement_view', ['id' => $invest->getId()]);
            }

            return $this->render('row/delete.html.twig', [
                'rowDeleteForm' => $rowDeleteForm->createView(),
                'row' => $row,
                'investment' => $invest
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }

    }
}
