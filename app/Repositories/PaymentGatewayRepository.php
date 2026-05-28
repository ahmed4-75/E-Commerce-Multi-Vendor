<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\PaymentGatewayInterface;

class PaymentGatewayRepository implements PaymentGatewayInterface
{
    public function paymentStart(Order $order, string $gateway, string $id)
    {
        $order->update([
            'payment_gateway' => $gateway,
            'gateway_order_id' => $id
        ]);
    }

    public function paymentDone(Order $order, string $id)
    {
        $order->update([
            'status' => 'paid',
            'transaction_id' => $id,
        ]);
    }
}
