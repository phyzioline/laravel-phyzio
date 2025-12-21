<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Traits\HasImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserService;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Dashboard\User\{StoreUserRequest,UpdateUserRequest};
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:users-index', only: ['index']),
            new Middleware('can:users-create', only: ['create', 'store']),
            // Removed middleware from edit/update - handled manually in methods to allow self-editing
            new Middleware('can:users-delete', only: ['destroy']),
        ];
    }

    public function __construct(public UserService $userService)
    {
    }
    use HasImage;
    public function index(Request $request)
    {
        $users = $this->userService->index();
        return view('dashboard.pages.users.index',compact('users'));
    }
    public function create()
    {
        $roles = $this->userService->create();
        return view('dashboard.pages.users.create',compact('roles'));
    }
    public function store(StoreUserRequest $storeUserRequest)
    {

        $data = $storeUserRequest->validated();
       $this->userService->store($data);

        Session::flash('message', ['type' => 'success', 'text' => __('User created successfully')]);
        return redirect()->route('dashboard.users.index');
    }

    public function edit(User $user)
    {
        // Allow users to edit their own profile OR require users-update permission
        if (auth()->id() !== $user->id && !auth()->user()->can('users-update')) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        $roles = $this->userService->create();
        return view('dashboard.pages.users.edit',compact('user','roles'));
    }

    public function update($id,UpdateUserRequest $updateUserRequest)
    {
        // Allow users to update their own profile OR require users-update permission
        if (auth()->id() != $id && !auth()->user()->can('users-update')) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        $data = $updateUserRequest->validated();
        $this->userService->update($id,$data);
        Session::flash('message', ['type' => 'success', 'text' => __('User updated successfully')]);
        if (auth()->id() == $id) {
            return redirect()->route('dashboard.users.edit', $id)->with('message', ['type' => 'success', 'text' => __('Profile updated successfully')]);
        }
        return redirect()->route('dashboard.users.index');
    }

    public function destroy($id)
    {
        $this->userService->destroy($id);
        Session::flash('message', ['type' => 'success', 'text' => __('User deleted successfully')]);
        return redirect()->route('dashboard.users.index');
    }
}
