<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = "kelas";
    protected $primaryKey = "kd_kelas";
    protected $keyType = "string";
    public $timestamps = true;
    public $incrementing = false;

    public function mahasiswa() : HasMany
    {
        return $this->hasMany(Mahasiswa::class, "kd_kelas", "kd_kelas");
    }
}
