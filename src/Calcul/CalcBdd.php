<?php


namespace App\Calcul;


use App\Entity\Row;

class CalcBdd
{
    private $total;

    public function totalValue($invests){

        $total = 0;

        foreach ($invests as $row)
        {
            $total += $row->getTotalValue();
        }
        return $total;
    }

}