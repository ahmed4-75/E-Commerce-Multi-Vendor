<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AddTranslationRequest;
use App\Http\Requests\RemoveTranslationRequest;
use App\Models\Translation;
use App\Services\TranslationService;

class TranslationsController extends Controller
{
    public function __construct(
        protected TranslationService $translationService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/Translations/category/{id}",
     *     summary="Get category translation languages",
     *     description="Retrieve available and unavailable translation languages for a specific category.",
     *     tags={"Translations"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Category ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Languages retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="available_langs",type="array",@OA\Items(type="string"),example={"en","ar"}),
     *             @OA\Property(property="available_langs_count",type="integer",example=2),
     *             @OA\Property(property="unavailable_langs",type="array",@OA\Items(type="string"),example={"fr","de"}),
     *             @OA\Property(property="unavailable_langs_count",type="integer",example=2)
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
     *         description="Category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Category not found.")
     *         )
     *     )
     * )
    */
    public function categoryLangs(int $id)
    {
        $data = $this->translationService->categoryLangs($id);
        return response()->json([$data],200);
    }

    /**
     * @OA\Post(
     *     path="/api/Translations/category/add/{id}",
     *     summary="Add translation to category",
     *     description="Add a new translation for a specific category.",
     *     tags={"Translations"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Category ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description","lang"},
     *             @OA\Property(property="name",type="string",maxLength=255,example="Electronics"),
     *             @OA\Property(property="description",type="string",example="Electrónica y dispositivos modernos"),
     *             @OA\Property(property="lang",type="string",ref="#/components/schemas/LanguagesEnum",description=" from LanguagesEnum",example="sp")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Translation added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="success"),
     *             @OA\Property(property="message",type="string",example="Translation added successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Language already exists for this category",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message",type="string",example="This language already exists for this category.")
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
     *        )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Category not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function addToCategory(int $id, AddTranslationRequest $request)
    {
        Gate::authorize('addToCategory', Translation::class);

        $this->translationService->addToCategory($id, $request);

        return response()->json([
            'status' => 'success',
            'message' => 'Translation added successfully'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/Translations/category/remove/{id}",
     *     summary="Remove category translation",
     *     description="Delete a specific translation from a category.",
     *     tags={"Translations"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Category ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"translation_id","lang"},
     *             @OA\Property(property="translation_id",type="integer",example=5),
     *             @OA\Property(property="lang",type="string",ref="#/components/schemas/LanguagesEnum",description="Value from LanguagesEnum",example="en")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Translation removed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Translation removed successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category or Translation not found",
     *         @OA\JsonContent(
     *             type="object",
     *            @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Category or Translation not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     )
     * )
     */
    public function removeFromCategory(int $id, RemoveTranslationRequest $request)
    {
        Gate::authorize('removeFromCategory', Translation::class);

        $this->translationService->removeFromCategory($id, $request);

        return response()->json([
            'status' => 'success',
            'message' => 'Translation removed successfully'
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/Translations/product/{id}",
     *     summary="Get product translation languages",
     *     description="Retrieve available and unavailable translation languages for a specific product owned by the authenticated user.",
     *     tags={"Translations"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Product ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Languages retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="available_langs",type="array",@OA\Items(type="string"),example={"en","ar"}),
     *             @OA\Property(property="available_langs_count",type="integer",example=2),
     *             @OA\Property(property="unavailable_langs",type="array",@OA\Items(type="string"),example={"fr","de"}),
     *             @OA\Property(property="unavailable_langs_count",type="integer",example=2 )
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
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Product not found.")
     *         )
     *     )
     * )
    */
    public function productLangs(int $id)
    {
        $data = $this->translationService->productLangs($id);
        return response()->json($data);
    }

    /**
     * @OA\Post(
     *     path="/api/Translations/product/add/{id}",
     *     summary="Add translation to product",
     *     description="Add a new translation for a specific product owned by the authenticated user.",
     *     tags={"Translations"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Product ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description","lang"},
     *             @OA\Property(property="name",type="string",maxLength=255,example="Laptop"),
     *             @OA\Property(property="description",type="string",example="Portátil de alto rendimiento"),
     *             @OA\Property(property="lang",type="string",ref="#/components/schemas/LanguagesEnum",description=" from LanguagesEnum",example="sp")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Translation added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="success"),
     *             @OA\Property(property="message",type="string",example="Translation added successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Language already exists for this product",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message",type="string",example="This language already exists for this product.")
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
     *            @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="Product not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function addToProduct(int $id, AddTranslationRequest $request)
    {
        Gate::authorize('addToProduct', Translation::class);

        $this->translationService->addToProduct($id, $request);

        return response()->json([
            'status' => 'success',
            'message' => 'Translation added successfully'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/Translations/product/remove/{id}",
     *     summary="Remove product translation",
     *     description="Delete a specific translation from a product owned by the authenticated user.",
     *     tags={"Translations"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="Product ID",@OA\Schema(type="integer", example=10)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"translation_id","lang"},
     *             @OA\Property(property="translation_id",type="integer",example=7),
     *             @OA\Property(property="lang",type="string",ref="#/components/schemas/LanguagesEnum",description=" from LanguagesEnum",example="sp")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Translation removed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Translation removed successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product or Translation not found",
     *         @OA\JsonContent(
     *            type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Product or Translation not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     )
     * )
    */
    public function removeFromProduct(int $id, RemoveTranslationRequest $request)
    {
        Gate::authorize('removeFromProduct', Translation::class);

        $this->translationService->removeFromProduct($id, $request);

        return response()->json([
            'status' => 'success',
            'message' => 'Translation removed successfully'
        ], 200);
    }
}
