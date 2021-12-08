<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'telegram_users', 'direction_id', 'id', 'id');
    }
    
    public function telegram_users()
    {
        return $this->hasMany(TelegramUser::class);
    }
}