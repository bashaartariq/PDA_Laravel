<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'doctors';


    protected $fillable = ['speciality_id', 'practice_location_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }
    public function practiceLocation()
    {
        return $this->belongsTo(PracticeLocation::class);
    }
    public function appointment()
    {
        return $this->hasMany(appointment::class, 'doctor_id');
    }
}
