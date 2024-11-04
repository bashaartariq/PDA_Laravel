<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseTypes extends Model
{
    use HasFactory;
    protected $table = 'case_types';


    protected $fillable = ['name'];
}
