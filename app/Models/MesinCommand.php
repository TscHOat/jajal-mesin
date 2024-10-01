<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesinCommand extends Model
{
    use HasFactory;
    protected $fillable = [
        'command',
        'mesin_absensi_id',
        'transmit_at',
        'completed_at',
        'response'
    ];
}
