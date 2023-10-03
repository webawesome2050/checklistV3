<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }


    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function permission()
    {
        return $this->hasMany(Permission::class);
    }

    public function hasPermission(string $permission): bool
    {
    //    return true;
        $permissonArray = [];
     
        foreach ($this->roles as $role) {
          
            foreach ($role->permission as $singlePermission) {
                $permissonArray[] = $singlePermission->key;
            }
        }
        // dd( $permissonArray);
        // dd(collect($permissonArray)->unique()->contains($permission));
        return collect($permissonArray)->unique()->contains($permission);
    }


    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

}
