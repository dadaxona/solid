<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'user_id', 'category_id', 'title', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
