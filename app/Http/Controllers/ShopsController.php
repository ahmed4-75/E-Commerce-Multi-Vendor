<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Resources\ShopBasicResource;
use App\Http\Resources\ShopResource;
use App\Services\ShopService;

class ShopsController extends Controller
{
    public function __construct(
        protected ShopService $shopService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/shops",
     *     tags={"Shops"},
     *     summary="Show shops",
     *     description="Returns all shops with basic information",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="All shops",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ShopBasicResource")),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All shops")
     *         )
     *     )
     * )
    */
    public function index()
    {
        $shops = $this->shopService->index();
        return response()->json(
            [
                'data' =>  ShopBasicResource::collection($shops),
                'status' => 'Success',
                'message' => 'All shops',
            ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/shops/show/{id}",
     *     tags={"Shops"},
     *     summary="Show a shop",
     *     description="Returns one shop with full information",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="shop id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Show shop",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/ShopResource"),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Show shop")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Shop not found")
     *         )
     *     )
     * )
    */
    public function show(int $id)
    {
        $shop = $this->shopService->show($id);

        if (!$shop) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Shop not found',
            ], 404);
        }

        return response()->json([
            'data' => new ShopResource($shop),
            'status' => 'Success',
            'message' => 'Show shop',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/shops/create",
     *     tags={"Shops"},
     *     summary="Create shop",
     *     description="Create a new shop",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","address","city","state","country","email","pincode","bank_name","bank_code","bank_country","bank_address","account_name","account_number"},
     *             @OA\Property(property="name", type="string", example="Shop 1"),
     *             @OA\Property(property="address", type="string", example="Shop address"),
     *             @OA\Property(property="city", type="string", example="marg"),
     *             @OA\Property(property="state", type="string", example="Cairo"),
     *             @OA\Property(property="country", type="string", example="Egypt"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="+2 01065484974"),
     *             @OA\Property(property="email", type="string", format="email", example="shop@test.com"),
     *             @OA\Property(property="pincode", type="string", example="12345"),
     *             @OA\Property(property="website", type="string", nullable=true, example="https://shop.test"),
     *             @OA\Property(property="bank_name", type="string", example="bank name"),
     *             @OA\Property(property="bank_code", type="string", example="bank code"),
     *             @OA\Property(property="bank_address", type="string", example="bank address"),
     *             @OA\Property(property="bank_country", type="string", example="Egypt"),
     *             @OA\Property(property="account_name", type="string", example="account name"),
     *             @OA\Property(property="account_number", type="string", example="123456789")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Shop created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop Created Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function store(CreateShopRequest $request)
    {
        $this->shopService->store($request);

        return response()->json([
            'status' => 'Success',
            'message' => 'Shop Created Successfully'
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/shops/update/{id}",
     *     tags={"Shops"},
     *     summary="Update shop",
     *     description="Update an existing shop",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="shop id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","address","city","state","country","email","pincode","bank_name","bank_code","bank_country","bank_address","account_name","account_number"},
     *             @OA\Property(property="name", type="string", example="Updated Shop"),
     *             @OA\Property(property="address", type="string", example="Updated address"),
     *             @OA\Property(property="city", type="string", example="Giza"),
     *             @OA\Property(property="state", type="string", example="Giza"),
     *             @OA\Property(property="country", type="string", example="Egypt"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="+2 01065484974"),
     *             @OA\Property(property="email", type="string", format="email", example="shop-updated@test.com"),
     *             @OA\Property(property="pincode", type="string", example="54321"),
     *             @OA\Property(property="website", type="string", nullable=true, example="https://shop-updated.test"),
     *             @OA\Property(property="bank_name", type="string", example="bank name"),
     *             @OA\Property(property="bank_code", type="string", example="bank code"),
     *             @OA\Property(property="bank_country", type="string", example="Egypt"),
     *             @OA\Property(property="bank_address", type="string", example="bank address"),
     *             @OA\Property(property="account_name", type="string", example="account name"),
     *             @OA\Property(property="account_number", type="string", example="987654321")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Shop updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop Updated Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found"
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function update(UpdateShopRequest $request, int $id)
    {

        $this->shopService->update($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Shop Updated Successfully',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/shops/delete/{id}",
     *     tags={"Shops"},
     *     summary="Delete shop",
     *     description="Delete a shop. Fails if there are related products.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="shop id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Shop deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found"
     *     ),
     *
     *     @OA\Response(
     *         response=409,
     *         description="Delete shop fail",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Can not delete the shop because it has related products. Move the products or delete them.")
     *         )
     *     )
     * )
    */
    public function delete(int $id)
    {
        try {
            $this->shopService->delete($id);

            return response()->json([
                'status' => 'Success',
                'message' => 'Shop deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 409);
        }
    }
}
