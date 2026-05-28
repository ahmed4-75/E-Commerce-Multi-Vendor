<?php

namespace App\Services;

use App\Http\Requests\PaymentSendRequest;
use App\Models\Order;
use App\Repositories\Contracts\PaymentGatewayInterface;
use libphonenumber\PhoneNumberUtil;
use \Illuminate\Http\Response;
// use Illuminate\Support\Facades\Storage;

class TapPaymentService extends BasePaymentService
{
    /**
     * Create a new class instance.
    */
    protected string $api_key;

    public function __construct(protected PaymentGatewayInterface $paymentGateway)
    {
        $this->base_url = config('services.tap.base_url');
        $this->api_key = config('services.tap.api_key');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            "Authorization" => "Bearer " . $this->api_key,
        ];
    }

    public function sendPayment(int $id, PaymentSendRequest $request): array
    {
        $order = Order::query()->whereKey($id)->firstOrFail();
        $transaction = 'txn_'.$id.'_'. time();
        if ($order->status === 'paid' or $order->transaction_id == $transaction) {
            return [
                'success' => false,
                'message' => 'Order already paid',
            ];
        }
        $phoneUtil = PhoneNumberUtil::getInstance();
        $parsed = $phoneUtil->parse($request->phone_number);
        $country_code = (string) $parsed->getCountryCode();
        $number = (string) $parsed->getNationalNumber();

        $response = $this->buildRequest(
            'POST',
            '/v2/charges/',
            [
                'amount' => $request->amount,
                'currency' => $request->currency,
                // 'reference' => ['transaction'=> $transaction,'order'=>335],
                'reference' => ['transaction'=> $transaction,'order'=>$order->id],
                'customer' => [
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'phone' => [
                        'country_code' => $country_code,
                        'number' => $number,
                    ]
                ],
                'description' => $request->description ?? 'Order Payment',
                'source' => ['id' => 'src_all'],
                'redirect' => ['url' => route('payment.callback')]
            ]
        );

        if ($response->successful()) {
            // Tap order id
            $tapOrderId = $response->json('tap_id');

            $this->paymentGateway->paymentStart($order, 'tap', $tapOrderId);

            return [
                'success' => true,
                'message' => 'Payment link generated successfully',
                'url' => $response->json('transaction.url'),
            ];
        }

        return [
            'success' => false,
            'message' => $response->body(),
        ];
    }

    public function verifyPayment(string $chargeId): array
    {
        $response = $this->buildRequest('GET', '/v2/charges/' . $chargeId);

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => $response->body(),
            ];
        }

        $data   = $response->json();
        $status = $data['status'] ?? null; // CAPTURED | CANCELLED | FAILED

        if ($status !== 'CAPTURED') {
            return [
                'success' => false,
                'message' => 'Payment not completed. Status: ' . $status,
                'status'  => $status,
            ];
        }

        return [
            'success' => true,
            'message' => 'Payment verified successfully',
            'status'  => $status,
            'data'    => $data,
        ];
    }


    public function callback(string $tapId): Response
    {
        $result = $this->verifyPayment($tapId);

        // Storage::put('tap_response.json', json_encode($result,JSON_PRETTY_PRINT |JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES));

        if ($result['success'] == false) {
            return response(['success' => false, 'message' => 'Payment verification failed'], 400);
        }

        $data = $result['data'];
        \Log::info('Tap callback received');
        \Log::info(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            // ['reference_id' => 333,] ['merchant_order_id' => 333,]
        $orderId = $data['reference']['order'];
        $transactionId = $data['reference']['transaction'];

        $order = Order::query()->whereKey($orderId)->where('payment_gateway', 'tap')->firstOrFail();
        $this->paymentGateway->paymentDone($order, $transactionId);

        return response(['success' => true, 'message' => 'Payment verified successfully'], 200);
    }
}
