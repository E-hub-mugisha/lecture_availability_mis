<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureAvailability extends Model
{
    use HasFactory;

    // Explicitly defining the table name (optional, since it follows Laravel's naming conventions)
    protected $table = 'lecturer_availabilities';

    // Specify the columns that are mass assignable
    protected $fillable = ['lecturer_id', 'day', 'date', 'start_time', 'end_time', 'status'];

    /**
     * Define the relationship with the Lecturer model.
     * A lecture availability belongs to one lecturer.
     */
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }
}
