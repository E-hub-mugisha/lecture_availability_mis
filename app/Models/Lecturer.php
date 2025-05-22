<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = ['user_id', 'staff_number','department_id', 'names','phone', 'address'];

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
    public function lecturerAvailabilities()
    {
        return $this->hasMany(LecturerAvailability::class, 'lecturer_id');
    }
}
