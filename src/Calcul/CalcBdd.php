<?php


namespace App\Calcul;


use App\Entity\Row;

class CalcBdd
{
    private $total;

    public function totalValue($rows){

        $total = 0;

        foreach ($rows as $row)
        {
            $total += $row->getTotalValue();
        }
        return $total;
    }

}