<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speciality extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'specialities';


    protected $fillable = ['name'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
