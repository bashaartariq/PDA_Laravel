<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class user extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'users';
    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'gender',
        'email',
        'password',
        'role',
        'dob',
    ];
    protected $hidden = [
        'password'
    ];
    protected $dates = ['dob', 'deleted_at'];
    public function patient()
    {
        return $this->hasOne(Patient::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }
}
