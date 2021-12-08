<?php

namespace Modules\Education\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'finished_at', 'certificate_number', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->belongsToMany(Attendance::class)->withPivot('came');
    }
    
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}