<?php

namespace Hcode\pagSeguro;


class Shopping {

    const PAC = 1;
    const SEDEX = 2;
    const OTHER = 3;

    private $address;
    private $type;
    private $cost;
    private $addressRequired;

}