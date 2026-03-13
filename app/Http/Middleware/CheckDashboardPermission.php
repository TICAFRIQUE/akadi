<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDashboardPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur a la permission dashboard.voir
        if (!auth()->check() || !auth()->user()->can('dashboard.voir')) {
            return redirect()->route('admin-no-permission')
                ->with('success', 'Bienvenue sur votre dashboard, mais vous n\'avez pas la permission d\'accéder au dashboard complet.');
        }

        return $next($request);
    }
}
