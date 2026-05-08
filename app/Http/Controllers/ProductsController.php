<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\LanguageRequest;
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
     *     @OA\Parameter(name="id", in="path", required=true, description="category id", @OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang", in="query", required=true, description="language", @OA\Schema(type="string", ref="#/components/schemas/LanguagesEnum", example="en")),
     *     security={{"sanctum":{}}},
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
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
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
     *                 @OA\Property(
     *                     property="product",
     *                     ref="#/components/schemas/ProductResource"
     *                 ),
     *                 @OA\Property(
     *                     property="comments",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/CommentResource")
     *                 ),
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
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
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
     *
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
     *             @OA\Property(property="message", type="string", example="Product Created Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function store(CreateProductRequest $request)
    {
        $this->productService->store($request);

        return response()->json([
            'status' => 'Success',
            'message' => 'Product Created Successfully',
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/products/update/{id}",
     *     tags={"Products"},
     *     summary="Update product",
     *     description="Update an existing product",
     *     @OA\Parameter(name="id", in="path", required=true, description="product id", @OA\Schema(type="integer", example=1)),
     *     security={{"sanctum":{}}},
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
     *     path="/api/products/delete/{id}",
     *     tags={"Products"},
     *     summary="Delete product",
     *     description="Delete a product with all translations and images",
     *     @OA\Parameter(name="id", in="path", required=true, description="product id", @OA\Schema(type="integer", example=1)),
     *     security={{"sanctum":{}}},
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
