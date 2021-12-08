<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'state','contact', 'region_id', 'description', 'image', 'price', 'published_at', 'telegram_chat_id'];

    // states: draft, published, waiting, deleted
    public function setDeleted()
    {
        $this->state = 'deleted';
        return $this;
    }
    public function setPublished()
    {
        $this->state = 'published';
        return $this;
    }
    public function setWaiting()
    {
        $this->state = 'waiting';
        return $this;
    }
    public function setDraft()
    {
        $this->state = 'draft';
        return $this;
    }
    
    public function scopeDeleted($query)
    {
        return $query->where('state', 'deleted');
    }
    public function scopePublished($query)
    {
        return $query->where('state', 'published');
    }
    public function scopeWaiting($query)
    {
        return $query->where('state', 'waiting');
    }
    public function scopeDraft($query)
    {
        return $query->where('state', 'draft');
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    //type
    
    public function scopeSells($query)
    {
        return $query->where('type', 'sell');
    }
    public function scopePurchases($query)
    {
        return $query->where('type', 'buy');
    }
}