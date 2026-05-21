<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\LanguageRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Show products by category",
     *     description="Returns products for a category based on a language",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, description="category id", @OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang", in="query", required=true, description="language", @OA\Schema(type="string", ref="#/components/schemas/LanguagesEnum", example="en")),
     *     @OA\Parameter(name="page",in="query",description="Page number",required=false,@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="All products",
     *         @OA\JsonContent(
     *             @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/ProductResource")),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/products?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/products?page=5"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/products?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/products"),
     *                 @OA\Property(property="per_page", type="integer", example=9),
     *                 @OA\Property(property="to", type="integer", example=9),
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/products?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All products")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="products does not exist",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The products for this category or language does not exist")
     *         )
     *     ),
     *
     *     @OA\Response(response=422,description="Validation error")
     * )
    */
    public function index(LanguageRequest $request ,int $id)
    {
        $lang = $request->validated('lang') ?? Auth::user()?->lang;

        $products = $this->productService->index($lang ,$id);

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'The products for this category or language does not exist'
            ], 404);
        }
        return ProductResource::collection($products)->additional([
            'status' => 'Success',
            'message' => 'All products',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/products/shop/{id}",
     *     summary="Get products for a specific shop",
     *     description="Returns products for a given shop filtered by language. Products are split into two groups: those matching the requested language and those in other languages.",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="The ID of the shop",@OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang",in="query",required=false,description="Language code to filter products by. Falls back to authenticated user's language if not provided.",@OA\Schema(ref="#/components/schemas/LanguagesEnum")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Shop products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="productsLang",type="array",description="Paginated products matching the requested language",@OA\Items(ref="#/components/schemas/ProductResource")),
     *                 @OA\Property(
     *                     property="links",
     *                     type="object",
     *                     @OA\Property(property="first", type="string", example="http://example.com/api/products/shop/1?lang=en&page=1"),
     *                     @OA\Property(property="last",  type="string", example="http://example.com/api/products/shop/1?lang=en&page=5"),
     *                     @OA\Property(property="prev",  type="string", nullable=true, example=null),
     *                     @OA\Property(property="next",  type="string", nullable=true, example="http://example.com/api/products/shop/1?lang=en&page=2")
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="from",type="integer", example=1),
     *                     @OA\Property(property="last_page",type="integer", example=5),
     *                     @OA\Property(property="path", type="string", example="http://example.com/api/products/shop/1?lang=en"),
     *                     @OA\Property(
     *                         property="links",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/products/shop/1?lang=en&page=1"),
     *                             @OA\Property(property="label", type="string", example="1"),
     *                             @OA\Property(property="active", type="boolean", example=true)
     *                         )
     *                     ),
     *                     @OA\Property(property="per_page",type="integer", example=20),
     *                     @OA\Property(property="to",type="integer", example=20),
     *                     @OA\Property(property="total",type="integer", example=100)
     *                 ),
     *                 @OA\Property(property="productsLang_count", type="integer", example=5, description="Total count of products in the requested language"),
     *                 @OA\Property(property="productsOtherLanguages",type="array",description="Paginated products in other languages",@OA\Items(ref="#/components/schemas/ProductResource")),
     *                 @OA\Property(property="productsOtherLanguages_count", type="integer", example=10, description="Total count of products in other languages"),
     *                 @OA\Property(property="products_count", type="integer", example=15, description="Total count of all products in the shop")
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Shop products")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Not found — either the shop doesn't exist or no products for this language",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The products for this shop or language does not exist")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error on the lang parameter",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The lang field must be a string.")
     *         )
     *     )
     * )
     */
    public function shopProducts(LanguageRequest $request ,int $id)
    {
        $lang = $request->validated('lang') ?? Auth::user()?->lang;

        $data = $this->productService->shopProducts($lang ,$id);

        if ($data['productsLang']->isEmpty()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'The products for this shop or language does not exist'
            ], 404);
        }
        return response()->json([
            'data' => [
                'productsLang' => ProductResource::collection($data['productsLang']),
                'productsLang_count' => $data['productsLang_count'],
                'productsOtherLanguages' => ProductResource::collection($data['productsOtherLanguages']),
                'productsOtherLanguages_count' => $data['productsOtherLanguages_count'],
                'products_count' => $data['products_count']
            ],
            'status' => 'Success',
            'message' => 'Shop products',
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/products/banned",
     *     summary="Get all banned (soft-deleted) products",
     *     description="Returns paginated soft-deleted products filtered by language, with their translations, shop, and user data.",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="lang",in="query",required=false,description="Language code to filter products by. Falls back to authenticated user's language if not provided.",@OA\Schema(ref="#/components/schemas/LanguagesEnum")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Banned products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data",type="array",description="Paginated list of banned products",@OA\Items(ref="#/components/schemas/ProductResource")),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Banned products")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No banned products found for the given language",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The banned products for this shop or language does not exist")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error on the lang parameter",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The lang field must be a string.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function bannedProducts(LanguageRequest $request)
    {
        $lang = $request->validated('lang') ?? Auth::user()?->lang;

        $products = $this->productService->bannedProducts($lang);

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'The banned products for this shop or language does not exist'
            ], 404);
        }
        return ProductResource::collection($products)->additional([
            'status' => 'Success',
            'message' => 'Banned products',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/products/search/{id}",
     *     summary="Search products by name within a category",
     *     description="Search for products within a specific category filtered by language and product name keyword. Returns paginated results with translations and images.",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="The ID of the category to search within",@OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang",in="query",required=false,description="Language code to filter products by. Falls back to authenticated user's language if not provided.",@OA\Schema(ref="#/components/schemas/LanguagesEnum")),
     *     @OA\Parameter(name="nameKey",in="query",required=true,description="Keyword to search for in product names",@OA\Schema(type="string", example="phone")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Search results retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data",type="array",description="Paginated list of matched products",@OA\Items(ref="#/components/schemas/ProductResource")),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string", example="http://example.com/api/products/search/1?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://example.com/api/products/search/1?page=3"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/products/search/1?page=2")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=9),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/products/search/1"),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/products/search/1?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=20),
     *                 @OA\Property(property="last_page", type="integer", example=3)
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Search results")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No products found or category does not exist",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message",type="string",example="No products found for the given search query and language")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error — nameKey is missing or lang is not supported",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message",type="string",example="The nameKey field is required.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function search(int $id, LanguageRequest $requestLang, Request $request)
    {
        $lang = $requestLang->validated('lang') ?? Auth::user()?->lang;
        $request->validate(['nameKey' => 'required|string']);
        $products = $this->productService->search($id, $lang, $request);

        if ($products->count() === 0) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No products found for the given search query and language'
            ], 404);
        }
        return ProductResource::collection($products)->additional([
            'status' => 'Success',
            'message' => 'Search results',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/product/show/{id}",
     *     tags={"Products"},
     *     summary="Show a product",
     *     description="Returns one product based on a language",
     *     @OA\Parameter(name="id", in="path", required=true, description="product id", @OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang", in="query", required=true, description="language", @OA\Schema(type="string", ref="#/components/schemas/LanguagesEnum", example="en")),
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Show product",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="product",ref="#/components/schemas/ProductResource"),
     *                 @OA\Property(property="comments",type="array",@OA\Items(ref="#/components/schemas/CommentResource")),
     *                 @OA\Property(
     *                     property="links",
     *                     type="object",
     *                     @OA\Property(property="first", type="string", example="http://example.com/api/products/show/1?page=1"),
     *                     @OA\Property(property="last", type="string", example="http://example.com/api/products/show/1?page=5"),
     *                     @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                     @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/products/show/1?page=2")
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="from", type="integer", example=1),
     *                     @OA\Property(property="last_page", type="integer", example=5),
     *                     @OA\Property(property="path", type="string", example="http://example.com/api/products/show/1"),
     *                    @OA\Property(property="per_page", type="integer", example=9),
     *                     @OA\Property(property="to", type="integer", example=9),
     *                     @OA\Property(property="total", type="integer", example=50),
     *                     @OA\Property(
     *                         property="links",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/products/show/1?page=1"),
     *                             @OA\Property(property="label", type="string", example="1"),
     *                             @OA\Property(property="active", type="boolean", example=true)
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="show product")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="product does not exist",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *
     *     @OA\Response(response=422,description="Validation error")
     * )
    */
    public function show(LanguageRequest $request ,int $id)
    {
        $lang = $request->validated('lang') ?? Auth::user()?->lang;

        $data = $this->productService->show($lang ,$id);

        return response()->json([
            'data' => [
                'product' => new ProductResource($data['product']),
                'comments' => CommentResource::collection($data['comments'])
            ],
            'status' => 'Success',
            'message' => 'Show product',
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/products/create",
     *     tags={"Products"},
     *     summary="Create product",
     *     description="Create a new product",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "description", "lang", "quantity", "price", "category_id", "shop_id"},
     *                 @OA\Property(property="name", type="string", example="Product Name"),
     *                 @OA\Property(property="description", type="string", example="Product description"),
     *                 @OA\Property(property="lang",type="string",ref="#/components/schemas/LanguagesEnum",description=" from LanguagesEnum",example="en"),
     *                 @OA\Property(property="quantity", type="integer", minimum=0, example=10),
     *                 @OA\Property(property="price", type="number", format="float", example=99.99),
     *                 @OA\Property(property="discount", type="number", format="float", nullable=true, example=10.00),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="shop_id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="object",required={"page_name", "image_path"},
     *                         @OA\Property(property="page_name",type="string",ref="#/components/schemas/PagesNamesEnum",example="page_a"),
     *                         @OA\Property(property="image_path",type="string",format="binary")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Create product",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Product Created Successfully, Available only in en")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error — missing or invalid fields",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message",type="string",example="The description has already been taken.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category or Shop not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Category is missing translations — product cannot be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You cannot use this category for now because it is Missing translation, please waite for all translations to be available"
     *             )
     *         )
     *     )
     * )
    */
    public function store(CreateProductRequest $request)
    {
        $this->productService->store($request);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product Created Successfully, Available only in '.$request->lang
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/products/update/{id}",
     *     tags={"Products"},
     *     summary="Update product",
     *     description="Update an existing product",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, description="product id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"translation_id", "name", "description", "quantity", "price", "category_id", "shop_id"},
     *
     *                 @OA\Property(property="translation_id", type="integer", example=1,description="Must belong to the product being updated (translationable_id = route id, translationable_type = Product)"),
     *                 @OA\Property(property="name", type="string", example="Updated Product Name"),
     *                 @OA\Property(property="description", type="string", example="Updated description",description="Must be unique in translations table except for the current translation_id"),
     *                 @OA\Property(property="quantity", type="integer", minimum=0, example=10),
     *                 @OA\Property(property="price", type="number", format="float", example=99.99),
     *                 @OA\Property(property="discount", type="number", format="float", nullable=true, example=10.00),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="shop_id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="object",required={"page_name", "image_path"},
     *                         @OA\Property(property="page_name",type="string",ref="#/components/schemas/PagesNamesEnum",example="page_a"),
     *                         @OA\Property(property="image_path",type="string",format="binary")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Update product",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Product Updated Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Product does not exist")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function update(UpdateProductRequest $request, int $id)
    {
        $this->productService->update($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product Updated Successfully',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/ban/{id}",
     *     summary="Ban a product",
     *     description="Soft deletes a product by ID (moves it to banned products). The product can be retrieved later from the banned products endpoint.",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="The ID of the product to ban",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product banned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Product banned successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
    */
    public function ban(int $id)
    {
        $this->productService->ban($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product banned successfully',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/products/unban/{id}",
     *     summary="Unban product",
     *     description="Restore a soft deleted product",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Product ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product unbanned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Product unbanned successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
    */
    public function unban(int $id)
    {
        $this->productService->unban($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product unbanned successfully',
        ], 200);
    }
    /**
     * @OA\Delete(
     *     path="/api/products/delete/{id}",
     *     tags={"Products"},
     *     summary="Delete product",
     *     description="Delete a product with all translations and images",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, description="product id", @OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Delete product success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Product does not exist")
     *         )
     *     )
     * )
    */
    public function delete(int $id)
    {
        $this->productService->delete($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
