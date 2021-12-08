<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\CRM\Entities\Client;
use Modules\WMS\Entities\Warehouse;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
        'full_name',
        'email',
        'address_registred',
        'address_living',
        'estimated_address',
        'phone',
        'phone_additional',
        'description',
        'birth_date',
        'gender',
        'image',
        'status',
        'education_degree',
        'marriage',
        'department_id',
        'passport'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function client()
    {
        return $this->hasOne(Client::class);
    }
}