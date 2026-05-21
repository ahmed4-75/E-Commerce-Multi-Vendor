<?php

namespace App\Http\Controllers;

use App\Http\Requests\LanguageRequest;
use App\Http\Requests\AddQuantityCartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/carts",
     *     summary="Get user carts",
     *     description="Retrieve all carts for authenticated user",
     *     tags={"Carts"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Carts retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="carts",type="array",@OA\Items(ref="#/components/schemas/CartResource")),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Carts retrieved successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
    */
    public function index()
    {
        $carts = $this->cartService->index();

        return response()->json([
            'carts' =>$carts,
            'status' => 'Success',
            'message' => 'Carts retrieved successfully.',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/carts/create",
     *     summary="Create cart",
     *     description="Create a new cart for authenticated user",
     *     tags={"Carts"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Cart created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Cart created successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
    */
    public function store()
    {
        $this->cartService->store();

        return response()->json([
            'status' => 'Success',
            'message' => 'Cart created successfully.',
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/cart/add/{id}",
     *     summary="Add product to cart",
     *     description="Add a product to authenticated user's cart",
     *     tags={"Carts"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Cart ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id","quantity"},
     *
     *             @OA\Property(property="id",type="integer",example=5,description="Product ID"),
     *             @OA\Property(property="quantity",type="integer",example=2,description="Quantity of product")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product added to cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Product added to cart successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors",type="object",
     *                 @OA\Property(property="product",type="array",@OA\Items(type="string", example="Product already in cart."))
     *             )
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
     *         description="Cart or Product not found"
     *     )
     * )
    */
    public function addToCart(AddQuantityCartRequest $request, int $id)
    {
        $this->cartService->addToCart($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product added to cart successfully.',
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/cart/show/{id}",
     *     summary="Show cart details",
     *     description="Retrieve a specific cart with products translated to selected language",
     *     tags={"Carts"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Cart ID",@OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang",in="query",required=true,description="Language code",@OA\Schema(type="string",ref="#/components/schemas/LanguagesEnum",example="en")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart retrieved successfully",
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="cart",ref="#/components/schemas/CartResource"),
     *             @OA\Property(property="missing_translation",type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id",type="integer",example=7),
     *                     @OA\Property(property="reason",type="string",example="product exists but not in requested lang")
     *                 )
     *             ),
     *             @OA\Property(property="missing_count",type="integer",example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Cart is empty"
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function show(LanguageRequest $request, int $id)
    {
        $lang = $request->validated('lang') ?? Auth::user()?->lang;
        $data = $this->cartService->show($lang, $id);

        return response()->json([
            'cart' => new CartResource($data['cart'], $lang),
            'missing_translation' => $data['missing_translation'],
            'missing_count' => $data['missing_count']
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/cart/remove/{id}",
     *     summary="Remove product from cart",
     *     description="Remove a product from authenticated user's cart",
     *     tags={"Carts"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Cart ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(required={"product_id"},@OA\Property(property="product_id",type="integer",example=5,description="Product ID"))),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product removed from cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="Product removed from cart successfully.")
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
     *         description="Cart or Product not found"
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function removeFromCart(Request $request, int $id)
    {
        $this->cartService->removeFromCart($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product removed from cart successfully.',
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/delete/{id}",
     *     summary="Delete cart",
     *     description="Delete a cart for the authenticated user and restore product quantities.",
     *     tags={"Carts"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Cart ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Cart deleted successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cart].")
     *         )
     *     )
     * )
    */
    public function delete(int $id)
    {
        $this->cartService->delete($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Cart deleted successfully.',
        ], 200);
    }
}
