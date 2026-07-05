<?php

namespace App\Services\Payements;

use App\Services\Payements\PayementModeInterface;
use App\Models\Payement;

class PaypalService implements PayementModeInterface
{

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
