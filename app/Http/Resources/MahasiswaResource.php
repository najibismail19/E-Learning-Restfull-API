<?php

namespace App\Http\Resources;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "npm" => $this->npm,
            "nama" => $this->nama,
            "email" => $this->email,
            'kelas' => new KelasResource($this->kelas),
            "token" => $this->whenNotNull($this->token)
        ];
    }
}
