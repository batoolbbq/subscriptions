<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
 class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission = null, $guard = null)
    {
        $authGuard = auth($guard ?? 'web');

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $user = $authGuard->user();

        $user->loadMissing('roles.permissions');

        $permissions = $permission
            ? explode('|', $permission)
            : [$request->route()->getName()];

        foreach ($permissions as $perm) {
            foreach ($user->roles as $role) {
                if ($role->permissions->contains('name', $perm)) {
                    return $next($request);
                }
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}