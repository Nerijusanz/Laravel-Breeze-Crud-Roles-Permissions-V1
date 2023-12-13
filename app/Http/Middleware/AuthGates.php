<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class AuthGates
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            $roles = Role::with('permissions')->get();
            $permissionsArray = [];

            foreach ($roles as $role) {
                foreach ($role->permissions as $permission) {
                    $permissionsArray[$permission->title][] = $role->id;
                }
            }

            foreach ($permissionsArray as $permissionTitle => $permissionRolesArray) {

                Gate::define($permissionTitle, function ($user) use ($permissionRolesArray) {

                    return count(array_intersect($user->roles->pluck('id')->toArray(), $permissionRolesArray)) > 0;
                });
            }
        }

        return $next($request);
    }
}
