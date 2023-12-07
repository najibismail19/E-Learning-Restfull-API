<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            DB::beginTransaction();

            DB::table("kelas")->insert([
                "kd_kelas" => "SI 3 A",
                "nama" => "SI 3 A"
            ]);

            DB::table("kelas")->insert([
                "kd_kelas" => "SI 2 A",
                "nama" => "SI 2 A"
            ]);

            DB::table("kelas")->insert([
                "kd_kelas" => "SI 1 A",
                "nama" => "SI 1 A"
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw ($e);

        }
    }
}
