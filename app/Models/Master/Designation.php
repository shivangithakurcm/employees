<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table    = 'designations';
    protected $fillable = ['name'];
}