<!DOCTYPE html>
<html>
<head>
    <title>Appointment Booked</title>
</head>
<body>
    <h2>Appointment Confirmation</h2>

    <p>Hello {{ $appointment->lecturer->names }},</p>

    <p>A new appointment has been booked by {{ $appointment->student->name }}.</p>

    <ul>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->availability->date)->toFormattedDateString() }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->availability->start_time)->format('g:i A') }}</li>
    </ul>

    <p>Thank you for using our system.</p>
</body>
</html>
