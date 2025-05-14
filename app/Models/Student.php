<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'student_number', 'names'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function availability()
    {
        return $this->hasMany(StudentAvailability::class);
    }

    public function availabilities()
    {
        return $this->hasManyThrough(
            LectureAvailability::class,
            Appointment::class,
            'student_id',      // Foreign key on the appointments table
            'id',              // Foreign key on the lecture_availabilities table
            'id',              // Local key on the students table
            'availability_id'  // Local key on the appointments table
        );
    }
}
