<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function paymentStart(Order $order, string $gateway, string $id);
    public function paymentDone(Order $order, string $id);
}
