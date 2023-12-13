<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

use App\Models\User;
use App\Models\Role;

class UsersController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with('roles')->paginate();

        return view('admin.users.index',compact('users'));
    }


    public function create()
    {
        abort_if(Gate::denies('user_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::select('id','title')->get();

        return view('admin.users.create',compact('roles'));
    }


    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->safe()->except(['role_id']));
        $user->roles()->sync($request->validated('role_id'));

        return redirect()->route('admin.users.index');
    }


    public function show(User $user)
    {
        abort_if(Gate::denies('user_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show',compact('user'));
    }


    public function edit(User $user)
    {
        abort_if(Gate::denies('user_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::select('id','title')->get();

        $user->load('roles');

        return view('admin.users.edit',compact('roles','user'));
    }


    public function update(UpdateUserRequest $request, User $user)
    {

        $user->update($request->safe()->except(['role_id']));
        $user->roles()->sync($request->validated('role_id'));

        if(isset($request['password']) && $request['password'] != null){

            $validated = $request->validate([
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

        }

        return redirect()->route('admin.users.show',$user->id)->with('status', 'admin-users-updated');
    }


    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
