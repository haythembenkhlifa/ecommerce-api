<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Modules\Order\Models\Order;
use Modules\User\Actions\User\CreateUser;
use Modules\User\Actions\User\DeleteUser;
use Modules\User\Actions\User\UpdateUser;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Models\User;
use Modules\User\Transformers\UserResource;

class UserController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the user.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return UserResource::collection(User::paginate(config('user.users_per_page', 100)));
    }

    /**
     * Store a newly created user
     */
    public function store(UserRequest $userRequest)
    {
        return new UserResource((new CreateUser)($userRequest));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorize('view', User::class);
        return new UserResource($user);
    }

    /**
     * Update the specified user.
     */
    public function update(UserRequest $userRequest, User $user)
    {
        return new UserResource((new UpdateUser)($userRequest, $user));
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        (new DeleteUser)($user);
    }
}
