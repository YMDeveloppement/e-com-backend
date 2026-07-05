<?php

namespace App\Services;

use App\Events\OrderState;
use App\Factories\PayementFactory;
use App\Models\Payement;
use Illuminate\Support\Str;
use App\Jobs\ValideEncaissePayementJob;
use App\Services\Payements\PayementModeInterface;

class PayementService
{
    public function __construct(
        protected PayementFactory $payementFactory,

    ) {}

    public function paye(array $dataValidated, $order)
    {
        $payement_mode = $this->payementFactory->make($dataValidated['mode_payement']);
        $identifyValide = $payement_mode->valide_identity($dataValidated['cart_data']);
        if (!$identifyValide) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'card-validation' => ['Identification of card is not valid'],
            ]);
            throw $error;
        }
        
        if (!$identifyValide) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'card-validation' => ['Identification of card is not valid'],
            ]);
            throw $error;
        }
        
        $payement = $this->createPayement($dataValidated['cart_data'], $order);
        ValideEncaissePayementJob::dispatch($payement_mode , $payement, $order);
        // ValideEncaissePayementJob::dispatch($payement);
        
        return $payement;
    }

    public function createPayement($cart_data, $order)
    {
        $faker = \Faker\Factory::create('fr_FR'); // Configuration en français
        $payement = Payement::create([
            'user_id' => $order->user_id, // Remplacez selon vos IDs existants
            'order_id' => $order->id, // Remplacez selon vos IDs existants
            'methode_paiement' => 'carte_bancaire',
            'marque_carte' => $cart_data['Name'],
            'montant_paye' => $order->total, // Entre 10.00 et 5000.00
            'dernier_4_chiffres' => $faker->numerify('####'), // ex: 4532
            'code_confirm' => $faker->numerify('####'), // ex: 9812
            'id_transaction_externe' => 'ch_' . Str::random(24), // ex: Id Stripe/CMI
            'statut_transaction' => 'pendding',
        ]);

        return $payement;
    }

}
