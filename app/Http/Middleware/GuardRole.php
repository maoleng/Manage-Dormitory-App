<?php

namespace App\Http\Middleware;

use App\Models\Student;
use Closure;
use Illuminate\Http\Request;

class GuardRole
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
        if(!$this->checkRole()) {
            return response([
                'status' => false,
                'message' => 'Không phải sinh viên tự quản'
            ]);
        }

        return $next($request);
    }

    public function checkRole(): bool
    {
        $student = c('student');
        return Student::TU_QUAN === $student->role;
    }
}
