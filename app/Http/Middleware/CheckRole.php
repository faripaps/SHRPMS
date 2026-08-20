<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Support dynamic session role override for live demonstration
        $activeRole = session('demo_role', auth()->user()?->role ?? 'hr_admin');

        if (in_array('*', $roles) || in_array($activeRole, $roles)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Access restricted. Your current persona (' . strtoupper($activeRole) . ') does not have permission to view that module.');
    }
}
