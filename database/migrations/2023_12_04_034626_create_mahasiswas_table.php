<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string("npm", 100)->nullable(false);
            $table->string("nama", 100)->nullable(false)->unique("mahasiswa_nama_unique");;
            $table->string("email", 100)->nullable(false)->unique("mahasiswa_email_unique");;
            $table->string("password", 100)->nullable(false);
            $table->string("token", 100)->nullable()->unique("mahasiswa_token_unique");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
