<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'methode_paiement',
        'marque_carte',
        'montant_paye',
        'dernier_4_chiffres',
        'code_confirm',
        'id_transaction_externe',
        'statut_transaction',
        'date_paiement',
    ];
}
