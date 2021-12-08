<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientFamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'full_name',
        'relation_type',
        'work',
        'work_address',
        'salary'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
}