<?php

namespace Modules\CRM\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\WMS\Entities\Product;
use Modules\WMS\Entities\Warehouse;

class Deal extends Model
{
    // statuses: new, monitoring, monitoring_canceled, committee, committee_canceled, completed
    protected $fillable = [
            'id',
            'status',
            'client_id',
            'warehouse_id',
            'product_ids',
            'payment_term',
            // 'created_by',
            // 'created_by_conclusion',
            // 'monitored_by',
            // 'monitored_at',
            // 'committee_member',
            // 'committee_conclusion',
            // 'committee_date',
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'quantity', 'paid_price', 'discount');;
    }
}