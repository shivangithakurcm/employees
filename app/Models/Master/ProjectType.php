<?php
namespace App\Models\Master;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    protected $table = 'project_types';
    protected $fillable = ['name'];
}