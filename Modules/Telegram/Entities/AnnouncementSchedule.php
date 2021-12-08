<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['telegram_bot_id', 'announcement_id', 'status', 'planned_at', 'published_at'];
    // statuses: draft, requested, ignored, waiting, published
    public function setPublished()
    {
        $this->status = 'published';
        return $this;
    }
    public function setIgnored()
    {
        $this->status = 'ignored';
        return $this;
    }
    public function setWaiting()
    {
        $this->status = 'waiting';
        return $this;
    }
    public function setRequested()
    {
        $this->status = 'requested';
        return $this;
    }
    public function setDraft()
    {
        $this->status = 'draft';
        return $this;
    }
    
    public function scopeIgnored($query)
    {
        return $query->where('status', 'ignored');
    }
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    public function telegram_bot()
    {
        return $this->belongsTo(TelegramBot::class);
    }
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}