<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'lecturer_id',
        'availability_id',
        'status',
        'time',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * Get the student associated with the appointment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the lecturer associated with the appointment.
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    /**
     * Get the availability associated with the appointment.
     */
    public function availability()
    {
        return $this->belongsTo(LectureAvailability::class, 'availability_id');
    }

    /**
     * Scope a query to only include appointments with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
