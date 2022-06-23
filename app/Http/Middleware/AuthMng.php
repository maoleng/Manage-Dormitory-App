<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Models\Student;
use App\Models\Teacher;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthMng
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if ($authorization = $request->headers->get('Authorization')) {
            preg_match("/bearer ([^\ ]*)/i", $authorization, $match);
            $token = $match[1] ?? null;
        } else {
            $token = $request->query->get('token');
        }

        $device = Device::query()
            ->where('token', $token)
            ->first();

        if (!$device instanceof Device) {
            return $this->errorJson(Response::HTTP_UNAUTHORIZED);
        }

        $user = $device->teacher;
        if (!$user instanceof Teacher) {
            return $this->errorJson(Response::HTTP_UNAUTHORIZED);
        }

        App::singleton('teacher', static function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    protected function errorJson($statusCode = Response::HTTP_BAD_REQUEST): Response
    {
        return new JsonResponse([
            'status' => false,
            'message' => 'Ê, mày là ai vậy ?',
        ], $statusCode);
    }
}
