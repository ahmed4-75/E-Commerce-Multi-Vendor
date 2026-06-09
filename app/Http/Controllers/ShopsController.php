<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Resources\ShopBasicResource;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Services\ShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="All shops",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ShopBasicResource")),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/shops?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/shops?page=5"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/shops?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/shops"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/shops?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="to", type="integer", example=20),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All shops")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *           type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     )
     * )
    */
    public function index()
    {
        Gate::authorize('index', Shop::class);

        $shops = $this->shopService->index();

        return ShopBasicResource::collection($shops)->additional([
            'status' => 'Success',
            'message' => 'All shops',
        ]);
        # manually pagination
        // $metaLinks = $shops->linkCollection()
        // ->map(
        //     function ($link) {
        //         return [
        //             'url' => $link['url'],
        //             'label' => $link['label'],   # e.g. "&laquo; Previous", "1", "Next &raquo;"
        //             'active' => $link['active'],
        //         ];
        //     }
        // )->values()->all();
        // $links = [
        //     'first' => $shops->url(1),
        //     'last' => $shops->url($shops->lastPage()),
        //     'prev' => $shops->previousPageUrl(),
        //     'next' => $shops->nextPageUrl(),
        // ];
        // $meta = [
        //     'current_page' => $shops->currentPage(),
        //     'from' => $shops->firstItem(),
        //     'last_page' => $shops->lastPage(),
        //     'path' => $shops->path(),
        //     'links' => $metaLinks,
        //     'per_page' => $shops->perPage(),
        //     'to' => $shops->lastItem(),
        //     'total' => $shops->total(),
        // ];

        // return response()->json([
        //     'data' => ShopBasicResource::collection($shops),
        //     'links' => $links,
        //     'meta' => $meta,
        //     'status' => 'Success',
        //     'message' => 'All shops',
        // ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/my-shops",
     *     summary="Get authenticated user shops",
     *     tags={"Shops"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="My shops",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/ShopBasicResource")),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/my-shops?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/my-shops?page=5"),
     *                 @OA\Property(property="prev", type="string", example="http://example.com/api/my-shops?page=1"),
     *                 @OA\Property(property="next", type="string", example="http://example.com/api/my-shops?page=3")
     *             ),
     *
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/my-shops"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/my-shops?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=10)
     *             ),
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="My shops")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function myShops()
    {
        $shops = $this->shopService->myShops();

        return ShopBasicResource::collection($shops)->additional([
            'status' => 'Success',
            'message' => 'My shops',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/banned-shops",
     *     summary="Get all banned shops",
     *     description="Returns a paginated list of all banned (soft-deleted) shops",
     *     tags={"Shops"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Banned shops retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/ShopBasicResource")),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/banned-shops?page=1"),
     *                 @OA\Property(property="last",  type="string", example="http://example.com/api/banned-shops?page=5"),
     *                 @OA\Property(property="prev",  type="string", nullable=true, example=null),
     *                 @OA\Property(property="next",  type="string", nullable=true, example="http://example.com/api/banned-shops?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from",         type="integer", example=1),
     *                 @OA\Property(property="last_page",    type="integer", example=5),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/banned-shops"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/banned-shops?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page",type="integer", example=20),
     *                 @OA\Property(property="to",type="integer", example=20),
     *                 @OA\Property(property="total",type="integer", example=100)
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Banned shops")
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
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     )
     * )
     */
    public function bannedShops()
    {
        Gate::authorize('bannedShops', Shop::class);

        $shops = $this->shopService->bannedShops();

        return ShopBasicResource::collection($shops)->additional([
            'status' => 'Success',
            'message' => 'Banned shops',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/shops/search",
     *     summary="Search shops by name",
     *     description="Returns a paginated list of shops matching the search keyword",
     *     tags={"Shops"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="nameKey",in="query",description="Keyword to search for in shop names",required=true,@OA\Schema(type="string", example="pizza")),
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Search results retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/ShopBasicResource")),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/shops/search?page=1"),
     *                 @OA\Property(property="last",  type="string", example="http://example.com/api/shops/search?page=5"),
     *                 @OA\Property(property="prev",  type="string", nullable=true, example=null),
     *                 @OA\Property(property="next",  type="string", nullable=true, example="http://example.com/api/shops/search?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from",         type="integer", example=1),
     *                 @OA\Property(property="last_page",    type="integer", example=5),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/shops/search"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/banned-shops?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page",type="integer", example=20),
     *                 @OA\Property(property="to",type="integer", example=20),
     *                 @OA\Property(property="total",type="integer", example=100)
     *             ),
     *             @OA\Property(property="status",  type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Search shops"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - nameKey is required"
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     * )
     */
    public function search(Request $request)
    {
        $request->validate(['nameKey' => 'required|string']);
        $shop = $this->shopService->search($request);

        return ShopBasicResource::collection($shop)->additional([
            'status' => 'Success',
            'message' => 'Search shop',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/shop/show/{id}",
     *     tags={"Shops"},
     *     summary="Show a shop",
     *     description="Returns one shop with full information",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, description="shop id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Show shop",
     *         @OA\JsonContent(
     *             type="object",
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
     *            type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Shop not found")
     *         )
     *     )
     * )
    */
    public function show(int $id)
    {
        $shop = $this->shopService->show($id);

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
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop Created Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
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
        Gate::authorize('store', Shop::class);

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
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop Updated Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Shop not found.")
     *         )
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
        Gate::authorize('update', Shop::class);

        $this->shopService->update($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Shop Updated Successfully',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/shops/ban/{id}",
     *     summary="Ban a shop",
     *     description="Soft deletes a shop by ID (ban it from the platform)",
     *     tags={"Shops"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",description="Shop ID to ban",required=true,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Shop banned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",  type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop banned successfully.")
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
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *            type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Shop not found.")
     *         )
     *     )
     * )
    */
    public function ban(int $id)
    {
        Gate::authorize('ban', Shop::class);

        $this->shopService->ban($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Shop banned successfully.',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/shops/unban/{id}",
     *     summary="Unban a shop",
     *     description="Restores a soft-deleted (banned) shop by ID",
     *     tags={"Shops"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",description="Shop ID to unban",required=true,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Shop unbanned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",  type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop unbanned successfully.")
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
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Shop not found.")
     *         )
     *     ),
     * )
     */
    public function unban(int $id)
    {
        Gate::authorize('unban', Shop::class);

        $this->shopService->unban($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Shop unbanned successfully.',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/shops/delete-myShop/{id}",
     *     tags={"Shops"},
     *     summary="Delete my shop",
     *     description="Delete a shop. Fails if there are related products or not user Shop.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="shop id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Shop deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Shop not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=409,
     *         description="Delete shop fail",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Can not delete the shop because it has related products. Move the products or delete them.")
     *         )
     *     )
     * )
    */
    public function deleteMine(int $id)
    {
        try {
            $this->shopService->deleteMine($id);

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
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Shop not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=409,
     *         description="Delete shop fail",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Can not delete the shop because it has related products. Move the products or delete them.")
     *         )
     *     )
     * )
    */
    public function delete(int $id)
    {
        Gate::authorize('delete', Shop::class);

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
