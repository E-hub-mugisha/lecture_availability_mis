<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'staff_number','department_id', 'names'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    /**
     * Define the relationship with the LectureAvailability model.
     * A lecture has many available timeslots.
     */
    public function lectureAvailabilities()
    {
        return $this->hasMany(LectureAvailability::class, 'lecturer_id');
    }
}
