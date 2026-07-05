<?php

namespace App\Services\Payements;
use App\Services\Payements\PayementModeInterface;
use App\Models\Order;
use App\Models\Payement;
use Illuminate\Support\Str;


class MasterCartService implements PayementModeInterface
{

    public function valide_identity(array $identity)
    {
        // send request to CMI to valide tra,saction 
        return true;
    }

    public function moneyTransaction(Payement $payement)
    {
        // transaction of money by send request to company api
        // if(is done)
            // make is encaissed
            $payement->statut_transaction = "encaissed";
            $payement->save();
    }
}
