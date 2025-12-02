<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Traits\HasImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserService;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Dashboard\User\{StoreUserRequest,UpdateUserRequest};

class UserController extends Controller
{
    public function __construct(public UserService $userService){}
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
        $roles = $this->userService->create();
        return view('dashboard.pages.users.edit',compact('user','roles'));
    }

    public function update($id,UpdateUserRequest $updateUserRequest)
    {

        $data = $updateUserRequest->validated();
        $this->userService->update($id,$data);
        Session::flash('message', ['type' => 'success', 'text' => __('User updated successfully')]);
        return redirect()->route('dashboard.users.index');
    }

    public function destroy($id)
    {
        $this->userService->destroy($id);
        Session::flash('message', ['type' => 'success', 'text' => __('User deleted successfully')]);
        return redirect()->route('dashboard.users.index');
    }
}
