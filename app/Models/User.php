<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'level',
        'status',
        'role_id',
        'change_password',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissionsWithActions()
    {
        $role = $this->role() // رابطه belongsTo
        ->with(['permissions' => function ($query) {
            $query->withPivot(['can_view', 'can_insert', 'can_edit', 'can_delete']);
        }])
            ->first();

        if (!$role) return collect();

        return $role->permissions->map(function ($permission) {
            return (object)[
                'slug'        => $permission->slug,
                'can_view'    => (bool) $permission->pivot->can_view,
                'can_insert'  => (bool) $permission->pivot->can_insert,
                'can_edit'    => (bool) $permission->pivot->can_edit,
                'can_delete'  => (bool) $permission->pivot->can_delete,
            ];
        });
    }



    public function hasRole($role)
    {
        if(is_string($role)) {
            return $this->role->contains('name' , $role);
        }
        return !! $role->intersect($this->role)->count();
    }
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(typeUser::class, 'type_id');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function activeCodes(): HasMany
    {
        return $this->hasMany(ActiveCode::class);
    }

    public function logs()
    {
        return $this->hasMany(User_logs::class);
    }

    public function lastLogin()
    {
        return $this->hasOne(User_logs::class)
            ->where('action', 'login')
            ->where('status', true)
            ->latestOfMany();
    }
    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

}
