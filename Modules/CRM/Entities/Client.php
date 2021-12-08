<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'spouse', 'spouse_work', 'children_count', 'family_member_count', 'main_family_expense', 'home_type', 'home_owner' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clientFamilyMembers()
    {
        return $this->hasMany(ClientFamilyMember::class);
    }
}