<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    public function telegram_users()
    {
        return $this->hasMany(TelegramUser::class);
    }
}