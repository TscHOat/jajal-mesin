<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'pin', 'password_mesin'];

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id', 'id');
    }
}
