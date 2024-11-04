<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\States;
use App\Models\zip_codes;

class cities extends Model
{
    use HasFactory;
    protected $table = 'cities';

    protected $fillable = ['name', 'state_id'];

    public function state()
    {
        return $this->belongsTo(States::class);
    }

    public function zipCodes()
    {
        return $this->hasMany(zip_codes::class, "city_id");
    }
}
