<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesinAbsensi extends Model
{
    use HasFactory;
    protected $table = 'mesin_absensi';
    protected $fillable = ['sn', 'name', 'last_sync'];

    public function commands()
    {
        return $this->hasMany(MesinCommand::class);
    }
}
