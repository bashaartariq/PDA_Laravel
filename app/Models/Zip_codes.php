<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\cities;

class zip_codes extends Model
{
    use HasFactory;
    protected $table = 'zip_codes';

    protected $fillable = ['zip_code', 'city_id'];

    public function city()
    {
        return $this->belongsTo(cities::class);
    }
}
