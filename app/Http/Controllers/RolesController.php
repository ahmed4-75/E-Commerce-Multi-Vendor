<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;

class RolesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Get all roles and permissions",
     *     description="Retrieve paginated roles with their permissions, and all available permissions.",
     *     tags={"Roles"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Roles retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="All Roles and Their Permissions and All Available Permissions"),
     *             @OA\Property(property="data",type="object",
     *                 @OA\Property(property="roles",type="array",@OA\Items(ref="#/components/schemas/RoleResource")),
     *                 @OA\Property(property="permissions",type="array",@OA\Items(ref="#/components/schemas/PermissionResource"))
     *             )
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
     *     )
     * )
    */
    public function index()
    {
        Gate::authorize('index', Role::class);

        $roles = Role::with('permissions')->get();
        $permissions = Permission::get();

        return response()->json([
            'status' => 'Success',
            'message' => 'All Roles and Their Permissions and All Available Permissions',
            'data' => [
                'roles' => RoleResource::collection($roles),
                'permissions' => PermissionResource::collection($permissions)
            ]
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Create new role",
     *     tags={"Roles"},
     *     description="Create new role",
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name",type="string",maxLength=50,example="Manager")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Role Added Successfully")
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
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function store(CreateRoleRequest $request)
    {
        Gate::authorize('store', Role::class);

        Role::create($request->validated());

        return response()->json([
            'status' => 'Success',
            'message' => 'Role Added Successfully'
        ],201);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{role}",
     *     summary="Update role",
     *     tags={"Roles"},
     *     description="Update role",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="role",in="path",required=true,description="Role ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","permissions"},
     *             @OA\Property(property="name",type="string",example="Manager"),
     *             @OA\Property(property="permissions",type="array",minItems=1,@OA\Items(type="integer", example=1))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Role Edited Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Role Not Found")
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
     *         response=422,
     *         description="Validation error"
     *     )
     * )
    */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', Role::class);

        $role->update($request->validated());
        $role->permissions()->sync($request->permissions);

        return response()->json([
            'status' => 'Success',
            'message' => 'Role Edited Successfully'
        ],200);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{role}",
     *     summary="Delete role",
     *     tags={"Roles"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(name="role",in="path",required=true,description="Role ID",@OA\Schema(type="integer", example=1)),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Role Deleted successfully")
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
     *         description="Role not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Role Not Found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=409,
     *         description="Cannot delete owner role",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="Owner role cannot be deleted.")
     *         )
     *     )
     * )
    */
    public function destroy(Role $role)
    {
        Gate::authorize('destroy', Role::class);

        if($role->name == 'owner') {
            return response()->json([
                'status' => 'Error',
                'message' => 'Owner role cannot be deleted.'
            ],409);
        }

        $role->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Role Deleted successfully'
        ],200);
    }
}
