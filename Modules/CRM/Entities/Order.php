<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\WMS\Entities\Product;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'total', 'status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}