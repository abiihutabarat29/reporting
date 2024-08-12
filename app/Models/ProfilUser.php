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
        'banner',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profilIsComplete()
    {
        return !empty($this->nama_pkk) && !empty($this->nohp_kantor) && !empty($this->alamat_kantor);
    }
}
