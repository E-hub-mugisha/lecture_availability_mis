<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAvailability extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'availability_id',
        'date',
        'day',
        'start_time',
        'end_time',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function availability()
    {
        return $this->belongsTo(Appointment::class);
    }
}
