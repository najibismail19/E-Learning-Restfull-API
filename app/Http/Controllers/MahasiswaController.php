<?php

namespace App\Http\Controllers;

use App\Http\Requests\MahasiswaLoginRequest;
use App\Http\Requests\MahasiswaRegisterRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function register(MahasiswaRegisterRequest $request) 
    {
        $data = $request->validated();

        if(Mahasiswa::where("npm", $data["npm"])->count() != null) {
            throw new HttpResponseException(response([
                "code" => 409,
                "status" => "Conflict",
                "errors" => [
                    "message" => [
                        "Mahasiswa already exists"
                    ]
                ]
            ], 409));
        }

        $mahasiswa = new Mahasiswa($data);
        $mahasiswa->password = Hash::make($data["password"]);
        $mahasiswa->save();

        return response()->json([
            'code' => 201, 
            'status' => 'Created', 
            'data' => new MahasiswaResource($mahasiswa),
        ])->setStatusCode(201);
    }


    public function login(MahasiswaLoginRequest $request) 
    {
        $data = $request->validated();
        
        $mahasiswa = Mahasiswa::where("email", $data["email"])->first();

        if(!$mahasiswa || !Hash::check($data["password"], $mahasiswa->password)) {
            throw new HttpResponseException(response([
                "code" => 401,
                "status" => "Unauthorized",
                "errors" => [
                    "message" => [
                        "email or password wrong"
                    ]
                ]
            ], 401));
        }

        $mahasiswa->token = Str::uuid()->toString();
        $mahasiswa->save();

        return response()->json([
            "code" => 200,
            "status" => "Ok",
            "data" => new MahasiswaResource($mahasiswa) 
        ])->setStatusCode(200);
    }

    
    public function get(Request $request)
    {
        $mahasiswa = Auth::user();

        return response()->json([
            "code" => 200,
            "status" => "Ok",
            "data" => new MahasiswaResource($mahasiswa)
        ]); 
    }

    public function logout() 
    {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "code" => 200,
            "status" => "Ok",
            "data" => [
                "message" => [
                    "Success logout"
                ]
            ]
        ], 200);
    }
}
