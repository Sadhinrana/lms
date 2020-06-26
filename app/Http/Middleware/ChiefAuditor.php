<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ChiefAuditor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedRoles = ['headmaster','chief_auditor'];
        if (in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request); 
        }
        return response('You dont have permission to perform this action!', 500);
    }
}
