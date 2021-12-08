<?php

namespace Modules\WMS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CRM\Entities\Deal;
use Modules\CRM\Entities\Order;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'expiry_day', 'price', 'unit_id', 'description', 'unit_id'];


    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }
    public function deals()
    {
        return $this->belongsToMany(Deal::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}