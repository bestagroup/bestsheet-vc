<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['title', 'label', 'slug' , 'module'];

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)
            ->withPivot(['can_view', 'can_insert', 'can_edit', 'can_delete']);
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
}
