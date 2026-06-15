<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Hashidable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Hashidable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'country_code',
        'mobile',
        'password',
        'status',
        'device_id',
    ];

    protected $append = [
        'full_name',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'parent_id' => 'array',
    ];

    public function getchildrens()
    {
        $currentUserId = $this->id;
        return User::whereRaw("JSON_CONTAINS(parent_id, '\"$currentUserId\"')")
        ->get();
    }

    public function getDescendantIds()
    {
        $ids = [$this->id];
        $children = $this->getchildrens();
        foreach ($children as $child) {
            if(!in_array($child->id,$ids)){
                $ids = array_merge($ids, $child->getDescendantIds());
            }
        }
        return $ids;
    }

    public function coach()
    {
        return $this->hasOne(Coach::class, 'user_id');
    }


    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }


    public function getFullNameAttribute()
    {
        if ($this->first_name == null && $this->last_name == null) {
            return 'N/A';
        }
        return $this->first_name . ' ' . $this->last_name;
    }
}
