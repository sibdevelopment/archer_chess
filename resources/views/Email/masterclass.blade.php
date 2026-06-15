@component('mail::message')

<p style="font-size: 16px; color: #555;"><em>Dear Parents/Guardians,</em></p>

<p style="font-size: 16px; color: #555;">
    We hope this message finds you well.
</p>

<p style="font-size: 16px; color: #555;">
    This is a courteous reminder that {{ $student->first_name }} {{ $student->last_name }} is scheduled to attend the upcoming online Master Class conducted by Archer Chess Academy on {{ $studentDate }} at {{ $studentTime    }}.
</p>
<p style="font-size: 16px; color: #555;">
    We kindly request that your child log in 5–10 minutes early to ensure a prompt start and to resolve any potential technical issues in advance.
</p>

<p style="font-size: 16px; color: #555;">
    Please use the student portal to join the masterclass:
</p>

<p style="font-size: 16px; color: #555;">
    🔗 <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    <em>Username:</em> {{ $student->mobile }}<br>
    <em>Password:</em> {{ 'archer@' . $student->user->id }}
</p>
<br>
<p style="font-size: 16px; color: #555;">
    If you face any issues joining from the portal, you can use the link below:
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Masterclass Link:</strong> 
    <a href="{{ $masterclass->join_url }}" style="color: #007bff; text-decoration: none;">
        Join Masterclass
    </a>
</p>


<p style="font-size: 16px; color: #555;">
    Should you have any questions or need assistance accessing the session, please feel free to contact us.
</p>

<p style="font-size: 16px; color: #555;">
    We look forward to an engaging and productive class .
</p>

<br>

<p style="font-size: 16px; color: #555;">
    <em>Warm regards,</em><br>
    <em>Archer Chess Academy</em>
</p>

@endcomponent
