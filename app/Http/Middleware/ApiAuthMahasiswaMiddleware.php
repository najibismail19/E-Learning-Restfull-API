<?php

namespace App\Http\Middleware;

use App\Models\Mahasiswa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMahasiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header("Authorization");

        $authenticate = true;

        if(!$token) {
            $authenticate = false;
        }

        $mahasiswa = Mahasiswa::where("token", $token)->first();
        
        if(!$mahasiswa) {

            $authenticate = false;

        } else {

            Auth::login($mahasiswa);
        }


        if($authenticate) {
            return $next($request);
        } else {
            return response()->json([
                "code" => "401",
                "status" => "Unauthorized",
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
