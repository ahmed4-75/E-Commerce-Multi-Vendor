<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;

class CommentsController extends Controller
{
    public function __construct(
        protected CommentService $commentService,
    ) {}

    /**
     * @OA\Post(
     *     path="/api/comments/create",
     *     summary="Create a new comment",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content", "lang", "product_id", "user_id"},
     *             @OA\Property(property="content", type="string", example="This is a great product!"),
     *             @OA\Property(property="lang",type="string",ref="#/components/schemas/LanguagesEnum",description=" from LanguagesEnum",example="en"),
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Comment created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
    */
    public function store(CreateCommentRequest $request)
    {
        $this->commentService->store($request);

        return response()->json([
            'status' => 'Success',
            'message' => 'Comment created successfully'
            ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/comments/update/{id}",
     *     summary="Update an existing comment",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id",in="path",required=true,description="Comment ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Updated comment content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Comment updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
    */
    public function update(UpdateCommentRequest $request, int $id)
    {
        $this->commentService->update($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Comment updated successfully'
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/delete/{id}",
     *     summary="Delete a comment",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id",in="path",required=true,description="Comment ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Comment deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
    */
    public function delete(int $id)
    {
        $this->commentService->delete($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
