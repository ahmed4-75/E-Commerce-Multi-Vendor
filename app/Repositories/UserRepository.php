<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserInterface;
use App\Http\Requests\ChangeRoleRequest;
use App\Models\Role;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function index()
    {
        $users = User::withTrashed()->with([
            'roles:id,name',
            'roles.permissions'
        ])->paginate(15);
        $roles = Role::query()->get();

        return [$users, $roles];
    }

    public function changeRole(ChangeRoleRequest $request,User $user)
    {
        $user->roles()->sync($request->role_ids);
    }

    public function ban(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function activate(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
    }

    public function destroy(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
    }
}
