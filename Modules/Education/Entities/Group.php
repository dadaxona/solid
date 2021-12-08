<?php

namespace Modules\Education\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'course_id', 'room_id'];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}