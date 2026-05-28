<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentSendRequest;
use App\Services\PaymobPaymentService;
use App\Services\TapPaymentService;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function __construct(
        protected PaymobPaymentService $paymobPayment,
        protected TapPaymentService $tapPayment
        )
    {}

    /**
     * @OA\Post(
     *     path="/api/payment/process/{id}",
     *     summary="Create Tap payment",
     *     description="Generate Tap payment URL and redirect user to payment page.",
     *     tags={"Payments"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Order id",@OA\Schema(type="integer")),
     *     @OA\Parameter(name="gateway",in="query",required=true,description="Payment gateway",@OA\Schema(type="string",example="paymob",enum={"tap","paymob"})),
     *     @OA\Parameter(name="type",in="query",required=true,description="Paymob Payment type",@OA\Schema(type="string",example="OnlineCard",enum={"OnlineCard","MobileWallet","PayPal"})),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone_number","email","amount","currency"},
     *             @OA\Property(property="name",type="string",example="Ahmed Morgan"),
     *             @OA\Property(property="phone_number",type="string",example="+201012345678"),
     *             @OA\Property(property="email",type="string",format="email",example="ahmed@example.com"),
     *             @OA\Property(property="amount",type="number",format="float",example=150.50),
     *             @OA\Property(property="currency",type="string",example="EGP"),
     *             @OA\Property(property="description",type="string",nullable=true,example="Order Payment")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Payment link generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success",type="boolean",example=true),
     *             @OA\Property(property="message",type="string",example="Payment link generated successfully"),
     *             @OA\Property(property="url",type="string",example="https://checkout.tap.company/checkout/123456")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Payment failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success",type="boolean",example=false),
     *             @OA\Property(property="message",type="string",example="Invalid payment data")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message",type="string",example="The given data was invalid."),
     *             @OA\Property(property="errors",type="object")
     *         )
     *     )
     * )
    */
    public function paymentProcess(PaymentSendRequest $request, int $id)
    {
        //,string $type(Mobile Wallet,Online Card,PayPal)
        $type = $request->query('type');
        $gateway = $request->query('gateway');
        $data = match($gateway) {
            'tap' => $this->tapPayment->sendPayment( $id, $request),
            'paymob' => $this->paymobPayment->sendPayment($id, $request, $type),
            default => [
                'success' => false,
                'message' => 'Unsupported payment gateway'
            ]
        };

        return response()->json([
            'success' => $data['success'],
            'message' => $data['message'],
            'url' => $data['url'] ?? null,
        ], $data['success'] ? 200 : 400);
    }

    public function callback(Request $request)
    {
        if($request->has('tap_id')) {
            $tapId = $request->query('tap_id');
            $response = $this->tapPayment->callback($tapId);
        }else{
            $response = $this->paymobPayment->callback($request);
        }
        return $response;
    }
}
