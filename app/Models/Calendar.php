<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','label','start','end','all_day','url','location','description','guests'
    ];

    protected $casts = [
        'guests' => 'array',
        'all_day' => 'boolean',
    ];
}
