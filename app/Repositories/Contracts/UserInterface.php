<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\ChangeRoleRequest;
use App\Models\User;

interface UserInterface
{
    public function index();
    public function changeRole(ChangeRoleRequest $request,User $user);
    public function ban(int $id);
    public function activate(int $id);
    public function destroy(int $id);
}
