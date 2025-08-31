<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'name','slug' ,'original_name', 'type' ,'file_path', 'size','user_id','project_id','mime' , 'subject_id' , 'role', 'company_id'
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => $this->shouldSlug()
            ]
        ];
    }

    protected function shouldSlug()
    {
        return $this->id != 1;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
