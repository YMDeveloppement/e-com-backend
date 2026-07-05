<?php

namespace App\Services\Payements;

use App\Models\Payement;

class VisaCartService implements PayementModeInterface
{
        public function __construct(
        Payement $payement
    ){}
    public function valide_identity(array $identity)
    {
        // send request to CMI to valide tra,saction 
        return false;
    }

    public function moneyTransaction(Payement $payement)
    {
        //
    }
}
