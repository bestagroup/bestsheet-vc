<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'company_id', 'title', 'date', 'type', 'file_path'];

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
