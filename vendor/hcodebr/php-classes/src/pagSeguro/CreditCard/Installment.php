<?php

namespace Hcode\pagSeguro\CreditCard;

use Exception;
use DOMDocument;
use DOMElement;
use Hcode\pagSeguro\Config;

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

    public function getDOMElement():DOMElement{

        $dom = new DOMDocument();

        $installment = $dom->createElement("installment");
        $installment = $dom->appendChild($installment);
        
       

        $value = $dom->createElement("value", number_format($this->value, 2, ".", ""));
        $value = $installment->appendChild($value);

        $quantity = $dom->createElement("quantity", $this->quantity);
        $quantity = $installment->appendChild($quantity);

        $noInterestInstallmentQuantity = $dom->createElement("noInterestInstallmentQuantity", Config::MAX_INSTALLMENT_NO_INTEREST);
        $noInterestInstallmentQuantity = $installment->appendChild($noInterestInstallmentQuantity);



        return $installment;

    }
}