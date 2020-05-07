<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function redirect_user()
    {
       return Auth::user()->user_redirect();
    }

    public function getData(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $mostrar = $request->mostrar;

        if ($buscar == '') {
            $users = User::where('status',1)->orderBy('id','desc')->paginate($mostrar);
        } else {
            $users = User::where('status',1)->where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate($mostrar);
        }

        return [
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'users' => $users,
        ];
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        if ($request->super_admin) {
            $user->assignRole('super_admin');
        }else {
            $permissions = $inputs['permisions'];
            $user->givePermissionTo($permissions);
        }

        $permisions = array_column($user->permissions->toArray(), 'name');

        return response()->json([
            'result' => 'Exito',
            'permissions' => $permisions,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $permisions = array_column($user->permissions->toArray(), 'name');
        $super_admin = false;

        if ($user->hasRole('super_admin')) {
            $super_admin = true;
        }

        return response()->json([
            'user' => $user,
            'permissions' => $permisions,
            'super_admin' => $super_admin,
        ]);
    }

    public function update(Request $request)
    {
        $inputs = $request->all();
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != '') {
                $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->hasRole('super_admin')) {
            if (!$request->super_admin) {
                $user->removeRole('super_admin');
                $permissions = $inputs['permisions'];
                $user->syncPermissions($permissions);
            }
        }elseif($request->super_admin) {
            $permissions = $inputs['permisions'];
            $user->revokePermissionTo($permissions);
            $user->assignRole('super_admin');
        }else {
            $permissions = $inputs['permisions'];
            $user->syncPermissions($permissions);
        }

        return response()->json([
            'result' => 'Exito',
            'user' => $user,
        ]);
    }

    public function destroy(User $id)
    {
        $id->status = 0;
        $id->save();

        return response()->json([
            'result' => 'Exito',
            'user' => $id,
        ]);
    }

    public function test()
    {
        dd(Auth::user()->hasRole('super_admin'));
    }

}
