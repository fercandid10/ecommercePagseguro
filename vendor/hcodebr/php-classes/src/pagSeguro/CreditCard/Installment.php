<?php

namespace Hcode\pagSeguro\CreditCard;

class Installment {

    private $quantity;
    private $value;

    public function __construct(int $quantity, float $value){

        if ($quantity < 1 || $quantity > Config::MAX_INSTALLMENT){

            throw new Exception("Numero de parcelas invalidas");
            
        }

        if ($value <= 0){

            throw new Exception("Valor total invalido.");
            
        }

        $this->quantity = $quantity;
        $this->value = $value;
    }
}