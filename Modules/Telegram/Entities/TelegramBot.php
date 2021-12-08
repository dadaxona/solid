<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBot extends Model
{
    use HasFactory;
    protected $fillable = ['bot_name', 'token', 'status','is_admin_bot', 'strategy', 'settings'];
    protected $casts = [
        'settings' => 'json',
    ];

    public function telegramChats()
    {
        return $this->hasMany(TelegramChat::class);
    }
}