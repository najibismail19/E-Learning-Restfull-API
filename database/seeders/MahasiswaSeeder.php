<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            DB::beginTransaction();

            DB::table("mahasiswa")->insert([
                "npm" => "1111111111",
                "nama" => "testting",
                "email" => "test@gmail.com",
                "kd_kelas" => "SI 3 A",
                "password" => "rahasia",
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw ($e);

        }
    }
}
