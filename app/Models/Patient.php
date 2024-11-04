<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'patients';

    protected $primaryKey = 'patient_id';

    public $incrementing = false;

    protected $keyType = 'unsignedBigInteger';

    protected $fillable = [
        'home_phone',
        'cell_phone',
        'ssn',
        'address',
        'city',
        'zip',
        'state',
        'patient_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function cases()
    {
        return $this->hasMany(Cases::class, 'PID');
    }
}
