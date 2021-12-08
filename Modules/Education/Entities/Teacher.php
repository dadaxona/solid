<?php

namespace Modules\Education\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'course_id', 'description', 'experience'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}