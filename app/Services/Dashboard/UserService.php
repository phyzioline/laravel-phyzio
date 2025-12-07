<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Traits\HasImage;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use HasImage;
    public function __construct(public User $user){}

    public function index()
    {

        return $this->user->whereNot('id', auth()->user()->id)->latest()->paginate(100);
    }

    public function create()
    {
        return Role::select(['id','name'])->get();
    }

    public function store($data)
    {
        $data['password'] = Hash::make($data['password']);
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        } else {
            $data['image'] =  asset('default/default.png');
        }


        $user = $this->user->create($data);
        if(isset($data['role_id']))
        {
            DB::table('model_has_roles')->insert([
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id,
                'role_id' => $data['role_id']
            ]);
        }


        return $user;
    }

    public function update($id , $data)
    {
        $user = $this->user->findOrFail($id);

        $data['password'] = $data['password'] ? Hash::make($data['password']) : $user->password;
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        } else {
            $data['image'] =  asset('default/default.png');
        }
        $user->update($data);
        if (isset($data['role_id']) && !empty($data['role_id'])) {
            $criteria = ['model_id' => $user->id];
            $attributes = [
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id,
                'role_id' => $data['role_id']
            ];
            DB::table('model_has_roles')->updateOrInsert($criteria, $attributes);
        } else {
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        }

        return $user;

    }

    public function destroy($id)
    {
        $user = $this->user->findOrFail($id);
        $user->delete();
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        return $user;
    }

}
