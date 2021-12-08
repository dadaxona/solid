<?php

namespace Modules\Education\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;
    protected $fillable = [
        'certificate_id', 'registered_number', 'region', 'full_name', 'full_name_english', 'subject', 'subject_english', 'phone', 'given_date'
    ];
    
}