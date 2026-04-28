<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\LanguageRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriesController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Show categories",
     *     description="Returns the categories based on a language",
     *     @OA\Parameter(name="lang", in="query", required=true, description="language", @OA\Schema(type="string", enum={"ar","en","ur","sp"})),
     *     security={{"sanctum":{}}},
     * 
     * 
     *     @OA\Response(
     *         response=200,
     *         description="All categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/CategoryResource")),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All categories")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="categories does not exist",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The categories for this language does not exist")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function index(LanguageRequest $request)
    {
        $lang = $request->validated('lang') ?? Auth::user()?->lang;

        $categories = $this->categoryService->index($lang);

        if ($categories->isEmpty()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'The categories for this language does not exist'
            ], 404);
        }

        return response()->json([
            'data' => CategoryResource::collection($categories),
            'status' => 'Success',
            'message' => 'All categories',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/categories/create",
     *     tags={"Categories"},
     *     summary="Crete category",
     *     description="create the category",
     *     security={{"sanctum":{}}},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","description","lang","image_path"},
     *                 @OA\Property(property="name", type="string", example="devices"),
     *                 @OA\Property(property="description", type="string", example="devices description"),
     *                 @OA\Property(property="lang", type="string", enum={"ar","en","ur","sp"}, example="ar"),
     *                 @OA\Property(property="image_path", type="string", format="binary", description="file|mimes:pdf,jpeg,jpg,png|max:6120", example="category_image.jpg")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Create category",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Category Created Successfully"),
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function store(CreateCategoryRequest $request)
    {
        $this->categoryService->store($request);

        return response()->json
        ([
            'status' => 'Success',
            'message' => 'Category Created Successfully',
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/show/{id}",
     *     tags={"Categories"},
     *     summary="Show a category",
     *     description="Returns a category based on a language",
     *     @OA\Parameter(name="id", in="path", required=true, description="category id", @OA\Schema(type="integer", example=1)),
     *     @OA\Parameter(name="lang", in="query", required=true, description="language", @OA\Schema(type="string", enum={"ar","en","ur","sp"})),
     *     security={{"sanctum":{}}},
     * 
     *     @OA\Response(
     *         response=200,
     *         description="show a Category",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/CategoryResource"),
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Show Category")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="category does not exist",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="The category for this language does not exist")
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

        $category = $this->categoryService->show($lang ,$id);

        if (!$category) {
            return response()->json([
                'status' => 'Error',
                'message' => 'The category for this language does not exist'
            ], 404);
        }

        return response()->json([
            'data' => new CategoryResource($category),
            'status' => 'Success',
            'message' => 'Show Category',
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/categories/update/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     description="Returns a category based on a language",
     *     @OA\Parameter(name="id", in="path", required=true, description="category id", @OA\Schema(type="integer", example=1)),
     *     security={{"sanctum":{}}},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"translation_id","name","description","image_path"},
     *                 @OA\Property(property="translation_id", type="integer", description="exists:translations,id", example=2),
     *                 @OA\Property(property="name", type="string", description="unique:translations,name,$id", example="devices"),
     *                 @OA\Property(property="description", type="string", example="devices description"),
     *                 @OA\Property(property="image_path", type="string", format="binary", description="file|mimes:pdf,jpeg,jpg,png|max:6120", example="category_image.jpg")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="update a Category",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Category Updated Successfully")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function update(UpdateCategoryRequest $request ,int $id)
    {
        $this->categoryService->update($request ,$id);

        return response()->json
        ([
            'status' => 'Success',
            'message' => 'Category Updated Successfully',
        ],200);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/delete/{id}",
     *     tags={"Categories"},
     *     summary="Delete category",
     *     description="Delete a category and all translation ",
     *     @OA\Parameter(name="id", in="path", required=true, description="category id", @OA\Schema(type="integer", example=1)),
     *     security={{"sanctum":{}}},
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Delete Category Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Category deleted successfully")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Category does not exist")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=409,
     *         description="Delete Category fail",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Can not delete the category because it has related products. Move the products or Delete Them.")
     *         )
     *     ),
     * )
    */
    public function delete(int $id)
    {
        try {
            $this->categoryService->delete($id);
    
            return response()->json([
                'status' => 'Success',
                'message' => 'Category deleted successfully',
            ], 200);
    
        } catch (ModelNotFoundException $e) {
    
            return response()->json([
                'status' => 'Error',
                'message' => 'Category not found',
            ], 404);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 409); // 🔥 Conflict مش 404
        }
    }
}