<?php

namespace Modules\Game\Entities;

use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Education\Entities\Lesson;

class Mental extends Model
{
    use HasFactory;

    protected $fillable = ['numbers','answers','result', 'user_id', 'limit', 'number_of_tasks', 'number_delay', 'interval', 'room', 'type', 'lesson_id'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'numbers' => 'json',
        'answers' => 'json',
        'result' => 'json',
    ];

    /**
     * Get the user that owns the Mental
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

}