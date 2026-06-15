@component('mail::message')

<p style="font-size: 16px; color: #555;"><em>Dear Parents/Guardians,</em></p>

<p style="font-size: 16px; color: #555;">
    We hope this message finds you well.
</p>

<p style="font-size: 16px; color: #555;">
    This is a courteous reminder that {{ $student->first_name }} is invited to participate in the upcoming <strong>{{ $tournament->name }}</strong> conducted by Archer Chess Academy on {{ $studentDateTime }} at {{ $studentTime }}.
</p>

<p style="font-size: 16px; color: #555;">
    We kindly request that your child log in 10 minutes early to ensure a smooth setup and to resolve any potential technical issues in advance.
</p>

<p style="font-size: 16px; color: #555;">
    <strong>How to create a Lichess account (if not done already):</strong>
</p>
<ol style="font-size: 16px; color: #555;">
    <li>Visit <a href="https://lichess.org">https://lichess.org</a></li>
    <li>Click on “Sign in” (top right corner), then select “Register”</li>
    <li>Fill in your preferred username, email, and password</li>
    <li>Agree to the terms and click <strong>Register</strong></li>
    <li>Verify your email (if prompted) to complete the process</li>
</ol>

<p style="font-size: 16px; color: #555;">
    After creating your Lichess account, please log in to the Archer Chess student portal to access the tournament:
</p>

<p style="font-size: 16px; color: #555;">
    🔗 <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    <em>Username:</em> {{ $student->user->mobile }}<br>
    <em>Password:</em> {{ 'archer@' . $student->user->id }}
</p>
<br>

<p style="font-size: 16px; color: #555;">
    If you face any issues joining from the portal, you can use the link below:
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Tournament Link:</strong> 
    <a href="{{ $tournament->link }}" style="color: #007bff; text-decoration: none;">
        {{ $tournament->link }}
    </a>
</p>



<p style="font-size: 16px; color: #555;">
    This tournament is an excellent opportunity for students to apply their skills in a friendly, competitive environment.
</p>

<p style="font-size: 16px; color: #555;">
    Should you have any questions or need assistance accessing the tournament, please feel free to contact us.
</p>

<br>

<p style="font-size: 16px; color: #555;">
    <em>Warm regards,</em><br>
    <em>Archer Chess Academy</em>
</p>

@endcomponent
