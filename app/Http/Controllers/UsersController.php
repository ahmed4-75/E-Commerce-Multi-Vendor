<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ChangeRoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UsersController extends Controller
{
    public function __construct(
        protected UserService $userService
    ){}

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users and roles",
     *     description="Retrieve paginated users with their roles and permissions, and all available roles.",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All Users and Their Roles and All Available Roles"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="users",
     *                     type="object",
     *                     @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/UserResource")),
     *                     @OA\Property(
     *                         property="links",
     *                         type="object",
     *                         @OA\Property(property="first", type="string", example="http://example.com/api/products?page=1"),
     *                         @OA\Property(property="last", type="string", example="http://example.com/api/products?page=5"),
     *                         @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                         @OA\Property(property="next", type="string", nullable=true, example="http://example.com/api/products?page=2")
     *                     ),
     *                     @OA\Property(
     *                         property="meta",
     *                         type="object",
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="from", type="integer", example=1),
     *                         @OA\Property(property="last_page", type="integer", example=5),
     *                         @OA\Property(property="path", type="string", example="http://example.com/api/products"),
     *                         @OA\Property(property="per_page", type="integer", example=9),
     *                         @OA\Property(property="to", type="integer", example=9),
     *                         @OA\Property(property="total", type="integer", example=50),
     *                         @OA\Property(
     *                             property="links",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="url", type="string", nullable=true, example="http://localhost/Ecommerce/public/api/products?page=1"),
     *                                 @OA\Property(property="label", type="string", example="1"),
     *                                 @OA\Property(property="active", type="boolean", example=true)
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="roles",type="array",@OA\Items(ref="#/components/schemas/RoleResource"))
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     )
     * )
    */
    public function index()
    {
        Gate::authorize('index', User::class);

        [$users, $roles] = $this->userService->index();

        return response()->json([
            'status' => 'Success',
            'message' => 'All Users and Their Roles and All Available Roles',
            'data' => [
                'users' => UserResource::collection($users),
                'roles' => RoleResource::collection($roles)
            ]
        ],200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/change-role/{id}",
     *     summary="Change user roles",
     *     description="Assign one or more roles to a specific user.",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="User ID",@OA\Schema(type="integer", example=5)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role_ids"},
     *             @OA\Property(property="role_ids",type="array",minItems=1,@OA\Items(type="integer", example=2))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Role changed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="User Role Changed Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Insufficient permissions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     )
     * )
    */
    public function changeRole(ChangeRoleRequest $request,int $id)
    {
        Gate::authorize('changeRole', User::class);

        $this->userService->changeRole($request, $id);

        return response()->json([
            'status' => 'Success',
            'message' => 'User Role Changed Successfully'
        ],200);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/ban/{id}",
     *     summary="Ban user",
     *     description="Soft delete (ban) a user.",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="User ID",@OA\Schema(type="integer",example=5)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User banned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="User is Banned Successfully")
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
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
    */
    public function ban(int $id)
    {
        Gate::authorize('ban', User::class);

        $this->userService->ban($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'User is Banned Successfully'
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/users/activate/{id}",
     *     summary="Activate user",
     *     description="Restore a previously banned (soft deleted) user.",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="User ID",@OA\Schema(type="integer",example=5)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User activated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="User Activated Successfully")
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
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="No query results for model [App\Models\User].")
     *         )
     *     )
     * )
    */
    public function activate(int $id)
    {
        Gate::authorize('activate', User::class);

        $this->userService->activate($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'User Activated Successfully'
        ],200);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/delete/{id}",
     *     summary="delete user",
     *     description="Delete a user.",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="id",in="path",required=true,description="User ID",@OA\Schema(type="integer",example=5)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Success"),
     *             @OA\Property(property="message",type="string",example="User is Deleted Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status",type="string",example="Error"),
     *             @OA\Property(property="message",type="string",example="User not found.")
     *         )
     *     )
     * )
    */
    public function destroy(string $id)
    {
        Gate::authorize('destroy', User::class);

        $this->userService->destroy($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'User is Deleted Successfully'
        ],200);
    }
}
