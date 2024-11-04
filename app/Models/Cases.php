<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cases extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cases';
    protected $fillable = [
        'DOA',
        'insurance_id',
        'firm_id',
        'practice_location_id',
        'category',
        'purpose_of_visit',
        'case_type',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PID');
    }
    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }
    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
    public function practiceLocation()
    {
        return $this->belongsTo(PracticeLocation::class);
    }
    public function appointment()
    {
        return $this->hasMany(appointment::class, 'case_id');
    }
}
