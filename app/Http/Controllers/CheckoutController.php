<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Services\OrderService;
use App\Services\PayementService;

class CheckoutController extends Controller
{

    public function __construct(
        protected OrderService $orderService,
        protected PayementService $payementService
    ) {}


    public function valide_payement(CheckoutRequest $request)
    {

        $dataValidated = $request->validated();

        $order = $this->orderService->valide_order($dataValidated);

        if (!$order) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'card-validation' => ['order not correct'],
            ]);
            throw $error;
        }
        $payement = $this->payementService->paye($dataValidated, $order);

        return response()->json(['order' => $order, 'payement' =>  $payement]);
    }
}
