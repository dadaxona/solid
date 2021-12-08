<?php

namespace Modules\Education\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;
    protected $casts = [
        'monday_from' => 'datetime:H:00',
        'monday_to' => 'datetime:H:00',
        'tuesday_from' => 'datetime:H:00',
        'tuesday_to' => 'datetime:H:00',
        'wednesday_from' => 'datetime:H:00',
        'wednesday_to' => 'datetime:H:00',
        'thursday_from' => 'datetime:H:00',
        'thursday_to' => 'datetime:H:00',
        'friday_from' => 'datetime:H:00',
        'friday_to' => 'datetime:H:00',
        'saturday_from' => 'datetime:H:00',
        'saturday_to' => 'datetime:H:00',
        'sunday_from' => 'datetime:H:00',
        'sunday_to' => 'datetime:H:00',
    ];
    protected $fillable = [
        'room_id',
        'group_id',
        'monday',
        'monday_from',
        'monday_to',
        'tuesday',
        'tuesday_from',
        'tuesday_to',
        'wednesday',
        'wednesday_from',
        'wednesday_to',
        'thursday',
        'thursday_from',
        'thursday_to',
        'friday',
        'friday_from',
        'friday_to',
        'saturday',
        'saturday_from',
        'saturday_to',
        'sunday',
        'sunday_from',
        'sunday_to'
    ];
    
    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }
}