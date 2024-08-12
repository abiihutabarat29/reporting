<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $table = "anggota";

    protected $fillable = [
        'kecamatan_id', 'desa_id', 'user_id', 'name', 'nohp', 'email', 'sk'
    ];
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
