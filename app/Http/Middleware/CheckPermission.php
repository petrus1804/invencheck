<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        return redirect()
            ->route($request->user()->defaultRouteName())
            ->with('access_denied', 'Anda Tidak Dapat Mengakses Halaman Ini Lagi');
    }
}