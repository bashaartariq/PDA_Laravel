<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurposeOfVisit extends Model
{
    use HasFactory;
    protected $table = 'purpose_of_visit';
    protected $fillable = ['name'];
}
