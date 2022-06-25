<?php

namespace App\Http\Middleware;

use App\Models\Teacher;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ManagerRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->checkRole()) {
            return response([
                'status' => false,
                'message' => 'Không phải quản lý kí túc xá'
            ]);
        }

        return $next($request);
    }

    public function checkRole(): bool
    {
        $teacher = c('teacher');
        return Teacher::QUAN_LY === $teacher->role;
    }
}
