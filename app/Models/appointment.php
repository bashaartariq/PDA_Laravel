<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'appointments';


    protected $fillable = [
        'case_id',
        'speciality_id',
        'practice_location_id',
        'date',
        'appointment_time',
        'appointment_type_id',
        'Duration',
        'Description',
        'doctor_id'
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    public function practiceLocation()
    {
        return $this->belongsTo(PracticeLocation::class);
    }
    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
