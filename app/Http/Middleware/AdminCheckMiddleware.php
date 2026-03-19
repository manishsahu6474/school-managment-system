<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {

            return $next($request);
        }
        $name = auth()->user()->name;
        $role = ucfirst(auth()->user()->role);
        $msg = "Hey $name, as a $role you do not have Admin permissions.";
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => $msg
            ], 403);
        }
        return redirect('/dashboard')->with('access_denied', $msg);
    }
}
