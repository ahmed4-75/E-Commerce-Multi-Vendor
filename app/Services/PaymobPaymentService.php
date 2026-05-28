<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentGatewayInterface;
// use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PaymentSendRequest;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Models\Order;
use Exception;

class PaymobPaymentService extends BasePaymentService
{
    /**
     * Create a new class instance.
    */
    protected string $api_key;
    /**
     * @var array<int>
     */
    protected array $integrations;

    public function __construct(protected PaymentGatewayInterface $paymentGateway)
    {
        $this->base_url = config('services.paymob.base_url');
        $this->api_key = config('services.paymob.api_key');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations = config('services.paymob.integrations');
    }

    protected function generateToken()
    {
        $response = $this->buildRequest(
            'POST',
            '/api/auth/tokens',
            ['api_key' => $this->api_key]
        );

        if (!$response->successful()) {
            throw new Exception('Unable to generate Paymob token');
        }

        return $response->json('token');
    }

    public function sendPayment(int $id, PaymentSendRequest $request, string $type): array
    {
        $order = Order::query()->whereKey($id)->firstOrFail();
        if ($order->status === 'paid' or $order->transaction_id != null) {
            return [
                'success' => false,
                'message' => 'Order already paid',
            ];
        }

        $this->header['Authorization'] =
            'Bearer ' . $this->generateToken();

        $response = $this->buildRequest(
            'POST',
            '/api/ecommerce/payment-links',
            [
                'amount_cents' => $request->amount * 100,
                'is_live' => false,
                'expires_at' => now()->addHours(10)->toIso8601String(),
                'reference_id' => $order->id,
                // 'reference_id' => 377,
                // 'payment_methods' => [$this->integrations['OnlineCard']],
                'payment_methods' => [$this->integrations[$type]],
                'email' => $request->email,
                'full_name' => $request->name,
                'phone_number' => $request->phone_number,
                'description' => $request->description ?? 'Order Payment',
                'notification_url' => 'https://identical-glimpse-cupbearer.ngrok-free.dev/api/payment/callback',
            ]
        );

        if ($response->successful()) {
            // Paymob order id
            $paymobOrderId = $response->json('id');

            $this->paymentGateway->paymentStart($order, 'paymob', $paymobOrderId);

            return [
                'success' => true,
                'message' => 'Payment link generated successfully',
                'url' => $response->json('client_url'),
            ];
        }

        return [
            'success' => false,
            'message' => $response->body(),
        ];
    }

    protected function verifyHmac(array $data, string $receivedHmac): bool
    {
        $bool = fn($val) => $val === true ? 'true' : 'false';

        $string =
            (string)($data['amount_cents'] ?? '') .
            (string)($data['created_at'] ?? '') .
            (string)($data['currency'] ?? '') .
            $bool($data['error_occured'] ?? false) .
            $bool($data['has_parent_transaction'] ?? false) .
            (string)($data['id'] ?? '') .
            (string)($data['integration_id'] ?? '') .
            $bool($data['is_3d_secure'] ?? false) .
            $bool($data['is_auth'] ?? false) .
            $bool($data['is_capture'] ?? false) .
            $bool($data['is_refunded'] ?? false) .
            $bool($data['is_standalone_payment'] ?? false) .
            $bool($data['is_voided'] ?? false) .
            (string)($data['order']['id'] ?? '') .
            (string)($data['owner'] ?? '') .
            $bool($data['pending'] ?? false) .
            (string)($data['source_data']['pan'] ?? '') .
            (string)($data['source_data']['sub_type'] ?? '') .
            (string)($data['source_data']['type'] ?? '') .
            $bool($data['success'] ?? false);

        $generated = hash_hmac('sha512', $string, config('services.paymob.hmac_key'));

        return hash_equals($generated, $receivedHmac);
    }

    public function callback(Request $request): Response
    {
        $payload = $request->all();

        $data = $payload['obj'] ?? [];

        // Storage::put('paymob_response.json', json_encode($data,JSON_PRETTY_PRINT |JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES));

        $receivedHmac = $payload['hmac'] ?? '';

        if (!$this->verifyHmac($data, $receivedHmac)) {
            \Log::warning('Paymob HMAC verification failed');
            return response(['success' => false, 'message' => 'Payment verification failed'], 400);
        }
        \Log::info('Paymob callback received');
        \Log::info(json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            // ['reference_id' => 333,] ['merchant_order_id' => 333,]
        $merchantOrderId = $data['order']['merchant_order_id'];
        $orderId = $merchantOrderId;
        $transactionId = $data['id'] ?? null;

        $order = Order::query()->whereKey($orderId)->where('payment_gateway', 'paymob')->firstOrFail();
        $this->paymentGateway->paymentDone($order, $transactionId);

        return response(['success' => true, 'message' => 'Payment verified successfully'], 200);
    }
}
