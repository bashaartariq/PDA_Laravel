<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeLocation extends Model
{
    use HasFactory;
    protected $table = 'practice_locations';

    protected $fillable = ['name'];
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}