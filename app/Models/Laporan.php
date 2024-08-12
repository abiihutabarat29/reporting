<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = "laporan";

    protected $fillable = [
        'kecamatan_id', 'desa_id', 'user_id', 'bidang_id', 'program_id', 'kegiatan_id', 'name', 'description', 'date', 'foto'
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
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
