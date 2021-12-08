<?php

namespace Modules\Education\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug', 'duration', 'description', 'course_id'];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}