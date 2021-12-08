<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChat extends Model
{
    use HasFactory;
    protected $fillable = ['chat_id', 'state', 'job_application_id', 'language', 'telegram_bot_id', 'is_admin_chat', 'last_published_at'];
    public function telegram_users()
    {
        return $this->belongsToMany(TelegramUser::class, 'telegram_chat_telegram_user');
    }
    public function telegram_bot()
    {
        return $this->belongsTo(TelegramBot::class);
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    public function scopePrivateChats($query)
    {
        return $query->where('chat_id', '>', 0);
    }
    public function scopePublicChats($query)
    {
        return $query->where('chat_id', '<', 0);
    }
}