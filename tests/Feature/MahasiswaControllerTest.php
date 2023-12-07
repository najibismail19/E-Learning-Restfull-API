<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Database\Seeders\KelasSeeder;
use Database\Seeders\MahasiswaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MahasiswaControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
    {
        $this->seed(KelasSeeder::class);

        $response = $this->post('/api/mahasiswa', [
            "npm" => "1111111111",
            "nama" => "testing",
            "email" => "test@gmail.com",
            "kd_kelas" => "SI 3 A",
            "password" => "rahasia",
        ]);


        $response->assertStatus(201)->assertJson([
            "code" => 201,
            "status" => "Created",
            "data" => [
                "npm" => "1111111111",
                "nama" => "testing",
                "email" => "test@gmail.com",
                "kelas" => [
                    "kd_kelas" => "SI 3 A",
                    "nama" => "SI 3 A"
                ]
            ]
        ]);

        $data = $response->json();
        
        $this->assertNotNull($data["data"]["id"]);

    }

     public function testRegisterValidationErrors400(): void
    {
        $this->seed(KelasSeeder::class);

        $response = $this->post('/api/mahasiswa', [
            "npm" => "",
            "nama" => "",
            "email" => "",
            "kd_kelas" => "",
            "password" => "",
        ]);

        $response->assertStatus(400)->assertJson([
            "code" => 400,
            "status" => "Bad Request",
            "errors" => [
                "npm" => [
                    "The npm field is required."
                ],
                "nama" => [
                    "The nama field is required."
                ],
                "email" => [
                    "The email field is required."
                ],
                "kd_kelas" => [
                    "The kd kelas field is required."
                ],
                "password" => [
                    "The password field is required."
                ],
            ]
        ]);

    }

    public function testRegisterMahasiswaAlreadyExistsError409() 
    {
        $this->seed([KelasSeeder::class, MahasiswaSeeder::class]);

        $response = $this->post('/api/mahasiswa',[
            "npm" => "1111111111",
            "nama" => "testaafad",
            "email" => "tesfdadaft@gmail.com",
            "kd_kelas" => "SI 3 A",
            "password" => "rahasia",
        ]);

        $response->assertStatus(409)->assertJson([
            "code" => 409,
            "status" => "Conflict",
            "errors" => [
                "message" => [
                    "Mahasiswa already exists"
                ]
            ]
        ]);

        $responseErrorsUnique = $this->post('/api/mahasiswa', [
            "npm" => "1111111111",
            "nama" => "testting",
            "email" => "test@gmail.com",
            "kd_kelas" => "SI 3 A",
            "password" => "rahasia",
        ]);

        $responseErrorsUnique->assertStatus(400)->assertJson([
            "code" => 400,
            "status" => "Bad Request",
            "errors" => [
                "nama" => [
                    "The nama has already been taken."
                ],
                "email" => [
                    "The email has already been taken."
                ]
            ]
        ]);
    }


// lOGIN TEST

    public function testLoginMahasiswaSuccess()
    {
        $this->testRegisterSuccess();

        $response = $this->post('/api/mahasiswa/login', [
            "email" => "test@gmail.com",
            "password" => "rahasia",
        ]);

        $response->assertStatus(200)->assertJson([
            "code" => 200,
            "status" => "Ok",
            "data" => [
                "npm" => "1111111111",
                "nama" => "testing",
                "email" => "test@gmail.com",
                "kelas" => [
                    "kd_kelas" => "SI 3 A",
                    "nama" => "SI 3 A"
                ]
            ]
        ]);

        $data =  $response->json();

        self::assertNotNull($data["data"]["id"]);
        self::assertNotNull($data["data"]["token"]);

    }
    public function testLoginValidationErrors400()
    {
        
        $response = $this->post('/api/mahasiswa/login', [
            "email" => "",
            "password" => "",
        ]);

        $response->assertStatus(400)->assertJson([
            "code" => 400,
            "status" => "Bad Request",
            "errors" => [
                "email" => [
                    "The email field is required."
                ],
                "password" => [
                    "The password field is required."
                ],
            ]
        ]);
    }


    public function testLoginInvalidMahasiswaErrors401()
    {
        
        $this->seed([KelasSeeder::class, MahasiswaSeeder::class]);

        $response = $this->post('/api/mahasiswa/login', [
            "email" => "salahbanget!",
            "password" => "pastiyasalahdong!",
        ]);

        $response->assertStatus(401)->assertJson([
            "code" => 401,
            "status" => "Unauthorized",
            "errors" => [
                "message" => [
                    "email or password wrong"
                ]
            ]
        ]);
    }

    // LOGOUT

    public function testLogoutSuccess()
    {
        $this->testRegisterSuccess();

        $response = $this->post('/api/mahasiswa/login', [
            "email" => "test@gmail.com",
            "password" => "rahasia",
        ]);

        $data =  $response->json();

        $this->assertNotNull(Mahasiswa::where("npm", $data["data"]["npm"])->first()->token);
        
        $this->post(uri : "/api/mahasiswa/logout", headers : [
            "Authorization" => $data["data"]["token"]
        ])->assertJson([
            "code" => 200,
            "status" => "Ok",
            "data" => [
                "message" => [
                    "Success logout"
                ]
            ]
        ]);

        $this->assertNull(Mahasiswa::where("npm", $data["data"]["npm"])->first()->token);
    }

    public function testLogoutFailed()
    {
        $this->post(uri : "/api/mahasiswa/logout", headers : [
            "Authorization" => "salah"
        ])->assertJson([
            "code" => "401",
            "status" => "Unauthorized",
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }

    // GET CURRENT
    public function testGetCurrentSuccess()
    {
         $this->testRegisterSuccess();

        $response = $this->post('/api/mahasiswa/login', [
            "email" => "test@gmail.com",
            "password" => "rahasia",
        ]);

        $data =  $response->json();

        $this->assertNotNull(Mahasiswa::where("npm", $data["data"]["npm"])->first()->token);
        
        $this->post(uri : "/api/mahasiswa/current", headers : [
            "Authorization" => $data["data"]["token"]
        ])->assertJson([
            "code" => 200,
            "status" => "Ok",
            "data" => [
                "id" => $data["data"]["id"],
                "nama" => $data["data"]["nama"],
                "email" => $data["data"]["email"],
                "kelas" => [
                    "kd_kelas" => $data["data"]["kelas"]["kd_kelas"],
                    "nama" => $data["data"]["kelas"]["nama"]
                ],
                "token" => $data["data"]["token"]
            ]
        ]);
    }

}
