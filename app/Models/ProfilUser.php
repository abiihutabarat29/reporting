<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilUser extends Model
{
    use HasFactory;

    protected $table = "profil_users";

    protected $fillable = [
        'user_id',
        'nama_pkk',
        'nohp_kantor',
        'alamat_kantor',
        'penasehat',
        'pengurus',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
