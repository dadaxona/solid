<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'second_name', 'full_name', 'region_id', 'direction_id', 'birth_date', 'registration_status', 'is_admin'];
    
    public function telegram_chats()
    {
        return $this->belongsToMany(TelegramChat::class, 'telegram_chat_telegram_user');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    
    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }
    
}