<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'user_id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)
            ->withPivot(['can_view', 'can_insert', 'can_edit', 'can_delete'])
            ->withTimestamps();
    }
    public function submenuPermissions(): hasMany
    {
        return $this->hasMany(SubmenuPermission::class);
    }
}
