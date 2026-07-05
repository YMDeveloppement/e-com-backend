<?php

namespace App\Services\Payements;
use App\Models\Payement;

interface PayementModeInterface{
    public function valide_identity(array $identity);
    public function moneyTransaction(Payement $payement);
}