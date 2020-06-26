<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Student
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
        $allowedRoles = ['headmaster','company_head', 'instructor'];
        if (in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request); 
        }
        return response('You dont have permission to perform this action!', 500);
    }
}
