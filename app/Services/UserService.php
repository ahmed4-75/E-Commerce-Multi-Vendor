<?php

namespace App\Services;

use App\Repositories\Contracts\UserInterface;
use App\Http\Requests\ChangeRoleRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserService
{
    public function __construct(
        protected UserInterface $userRepository
    ) {}

    public function index()
    {
        return $this->userRepository->index();
    }

    public function changeRole(ChangeRoleRequest $request,int $id)
    {
        $user = User::findOrFail($id);
        $user_isOwner = $user->roles()->where('name', 'owner')->exists();

        $me = Auth::user();
        $me_isOwner = $me->roles()->where('name', 'owner')->exists();

        if ($user_isOwner && !$me_isOwner) {
            abort(403,'The change role is prevented because the user account is owner and you are not owner.');
        }

        return $this->userRepository->changeRole($request, $user);
    }

    public function ban(int $id)
    {
        return $this->userRepository->ban($id);
    }

    public function activate(int $id)
    {
        return $this->userRepository->activate($id);
    }

    public function destroy(int $id)
    {
        return $this->userRepository->destroy($id);
    }
}

