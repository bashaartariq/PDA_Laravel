<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\cities;

class States extends Model
{
    use HasFactory;

    protected $table = 'states';


    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(cities::class, 'state_id');
    }
}
