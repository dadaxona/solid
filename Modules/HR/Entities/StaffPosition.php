<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffPosition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
}
