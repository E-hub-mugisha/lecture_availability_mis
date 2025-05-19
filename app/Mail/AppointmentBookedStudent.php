<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AppointmentBookedStudent extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Booked - Confirmation for Student',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_student',
            with: ['appointment' => $this->appointment],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
