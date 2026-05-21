<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrdersController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/orders/user",
     *     summary="Get authenticated user orders",
     *     description="Retrieve paginated orders for the authenticated user.",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="orders",
     *                 type="object",
     *                 @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/OrderResource")),
     *                 @OA\Property(
     *                     property="links",
     *                     type="object",
     *                     @OA\Property(property="first", type="string", example="http://example.com/api/orders/user?page=1"),
     *                     @OA\Property(property="last", type="string", example="http://example.com/api/orders/user?page=5"),
     *                     @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                     @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/orders/user?page=2")
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="from", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=5),
     *                     @OA\Property(property="path", type="string", example="http://example.com/api/orders/user"),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="to", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=50),
     *                     @OA\Property(
     *                         property="links",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/orders/user?page=1"),
     *                             @OA\Property(property="label", type="string", example="1"),
     *                             @OA\Property(property="active", type="boolean", example=true)
     *                         )
     *                     )
     *                 ),
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Orders User retrieved successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
    */
    public function index()
    {
        $orders = $this->orderService->index();

        return response()->json([
            'orders' => OrderResource::collection($orders),
            'status' => 'Success',
            'message' => 'Orders User retrieved successfully.',
        ]);
    }

        /**
     * @OA\Get(
     *     path="/api/orders/all",
     *     summary="Get all orders",
     *     description="Retrieve paginated orders.",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="orders",
     *                 type="object",
     *                 @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/OrderResource")),
     *                 @OA\Property(
     *                     property="links",
     *                     type="object",
     *                     @OA\Property(property="first", type="string", example="http://example.com/api/orders/all?page=1"),
     *                     @OA\Property(property="last", type="string", example="http://example.com/api/orders/all?page=5"),
     *                     @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                     @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/orders/all?page=2")
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="from", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=5),
     *                     @OA\Property(property="path", type="string", example="http://example.com/api/orders/all"),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="to", type="integer", example=10),
     *                     @OA\Property(property="total", type="integer", example=50),
     *                     @OA\Property(
     *                         property="links",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/orders/all?page=1"),
     *                             @OA\Property(property="label", type="string", example="1"),
     *                             @OA\Property(property="active", type="boolean", example=true)
     *                         )
     *                     )
     *                 ),
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example=" All Orders retrieved successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
    */
    public function allOrders()
    {
        $orders = $this->orderService->allOrders();

        return response()->json([
            'orders' => OrderResource::collection($orders),
            'status' => 'Success',
            'message' => 'All Orders retrieved successfully.',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/orders/create",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     description="Create a new order using cart products for the authenticated user.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cart_id","lang","first_name","last_name","email","address","phone","currency","payment_gateway"},
     *             @OA\Property(property="cart_id",type="integer",example=1),
     *             @OA\Property(property="lang",type="string",enum={"ar","en"},example="ar"),
     *             @OA\Property(property="first_name",type="string",maxLength=255,example="Ahmed"),
     *             @OA\Property(property="last_name",type="string",maxLength=255,example="Morgan"),
     *             @OA\Property(property="email",type="string",format="email",maxLength=255,example="ahmed@example.com"),
     *             @OA\Property(property="address",type="string",maxLength=500,example="Mansoura, Egypt"),
     *             @OA\Property(property="phone",type="string",maxLength=20,example="+20 1065484974"),
     *             @OA\Property(property="currency",type="string",maxLength=10,example="EGP"),
     *             @OA\Property(property="payment_gateway",type="string",maxLength=255,example="paypal")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="Order created successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Cart contains products without translations in requested language",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Cannot create order. There are 2 products in the cart that do not have translations in the requested language.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Cart does not belong to authenticated user",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This Cart does not belong to the authenticated user.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message",type="string",example="The given data was invalid."),
     *             @OA\Property(property="errors",type="object",
     *                 @OA\Property(property="cart_id",type="array",@OA\Items(type="string", example="The selected cart id is invalid.")),
     *                 @OA\Property(property="email",type="array",@OA\Items(type="string", example="The email field must be a valid email address."))
     *             )
     *         )
     *     )
     * )
    */
    public function store(CreateOrderRequest $request)
    {
        $this->orderService->store($request);

        return response()->json([
            'status' => 'Success',
            'message' => 'Order created successfully.',
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/order/show/{id}",
     *     tags={"Orders"},
     *     summary="Get a specific order",
     *     description="Retrieve a specific order for the authenticated user.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Order ID",@OA\Schema(type="integer",example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="order",ref="#/components/schemas/OrderResource"),
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="Order retrieved successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No query results for model [Order]."
     *             )
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $order = $this->orderService->show($id);

        return response()->json([
            'order' => new OrderResource($order),
            'status' => 'Success',
            'message' => 'Order retrieved successfully.',
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/update/{id}",
     *     tags={"Orders"},
     *     summary="Update order note",
     *     description="Update the note of a specific order for the authenticated user.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Order ID",@OA\Schema(type="integer",example=1)),
     *
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="note",type="string",nullable=true,example="Please deliver after 6 PM.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="Order updated successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(type="object",@OA\Property(property="message",type="string",example="No query results for model [Order]."))
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message",type="string",example="The given data was invalid."),
     *             @OA\Property(property="errors",type="object",
     *                 @OA\Property(property="note",type="array",@OA\Items(type="string",example="The note field must be a string."))
     *             )
     *         )
     *     )
     * )
    */
    public function update(UpdateOrderRequest $request, int $id)
    {
        $this->orderService->update($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Order updated successfully.'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/delete/{id}",
     *     tags={"Orders"},
     *     summary="Delete an order",
     *     description="Delete a specific order for the authenticated user.",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Order ID",@OA\Schema(type="integer",example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="Order deleted successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message",type="string",example="No query results for model [Order].")
     *         )
     *     )
     * )
    */
    public function delete(int $id)
    {
        $this->orderService->delete($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Order deleted successfully.',
        ]);
    }
}
