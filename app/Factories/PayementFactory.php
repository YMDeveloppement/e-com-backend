<?php

namespace App\Factories;
use App\Services\Payements\PaypalService;
use App\Services\Payements\VisaCartService;
use App\Services\Payements\MasterCartService;
class PayementFactory
{


    public function make(string $method)
    {
        return match ($method) {
            'paypal' => new PaypalService(),
            'cartVisa' => new VisaCartService(),
            'masterCard' => new MasterCartService(),
        };
        // return match ($method) {
        //     'paypal' => app(PaypalService::class),
        //     'cartVisa' => app(VisaCartService::class),
        //     'masterCard' => app(MasterCartService::class),
        // };
    }
}
