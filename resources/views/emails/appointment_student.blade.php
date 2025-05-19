<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
</head>
<body>
    <h2>Hello {{ $appointment->student->name }},</h2>
    <p>Your appointment with <strong>{{ $appointment->lecturer->names }}</strong> has been successfully booked.</p>
    <p><strong>Date:</strong> {{ $appointment->availability->date }}</p>
    <p><strong>Time:</strong> {{ $appointment->availability->start_time }} - {{ $appointment->availability->end_time }}</p>
    <p>Thank you!</p>
</body>
</html>
