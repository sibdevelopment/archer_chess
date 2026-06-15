@component('mail::message')

<p style="font-size: 16px; color: #555;"><strong>Dear {{ $student->first_name }} {{ $student->last_name }},</strong></p>

<p style="font-size: 16px; color: #555;">
    We are delighted to confirm your enrollment at <strong>Archer Chess Academy</strong>. Please find your login and enrollment details below:
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Portal Access:</strong> <a href="https://app.chesslang.com/login">https://app.chesslang.com/login</a><br>
    <strong>Portal ID:</strong> {{ $student->student_id ?? 'N/A' }}<br>
    <strong>Password:</strong> {{ $student->portal_password ?? 'N/A' }}
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Student Dashboard Access:</strong> <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    <strong>Username:</strong> {{ $student->mobile }}<br>
    <strong>Password:</strong> {{ 'archer@' . $student->user->id }}
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Enrollment Details:</strong><br>
    Enrolled Level: {{ $student->level->name ?? 'N/A' }}<br>
    Class Start Date: {{ toIndianDate($student_fee->start_date ?? now()) }}
</p>

<p style="font-size: 16px; color: #555;">
    The portal and student dashboard will allow you to track schedules, attendance, and progress throughout the program.
</p>

<p style="font-size: 16px; color: #555;">
    If you require any assistance with login or further information, please contact our support team at <a href="mailto:support@archerchessacademy.com">support@archerchessacademy.com</a>.
</p>

<p style="font-size: 16px; color: #555;">
    We look forward to seeing you continue your chess journey with us!
</p>

<br>

<p style="font-size: 16px; color: #555;">
    <em>Sincerely,</em><br>
    <em>Archer Chess Academy</em>
</p>

@endcomponent
