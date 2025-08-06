<?php

namespace App\Http\Middleware;

use App\Models\SubmenuPanel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckSubmenuPermission
{

     public function handle(Request $request, Closure $next, $permissionType, $submenuSlug)
    {
        $user = Auth::guard('panel')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roles = $user->roles()->pluck('roles.id');

        $submenu = SubmenuPanel::where('slug', $submenuSlug)->first();

        if (!$submenu) {
            abort(403, 'زیرمنو مورد نظر پیدا نشد.');
        }

        $access = DB::table('submenu_permission')
            ->whereIn('role_id', $roles)
            ->where('submenu_id', $submenu->id)
            ->where($permissionType, true)
            ->exists();

        if (!$access) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        return $next($request);
    }
}
