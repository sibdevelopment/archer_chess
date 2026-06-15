@component('mail::message')

<p style="font-size: 15px; color:#444;">
    Dear {{ $demoLeadEnquiry->kids_first_name }},
</p>

<p style="font-size: 15px; color:#444;">
    Thank you for registering for a trial class with <strong>Archer Chess Academy</strong>.  
    Please find below the login details for the student dashboard:
</p>

<p style="font-size: 15px; color:#444; line-height: 1.6;">
    <strong>URL:</strong> <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    <strong>Username:</strong> {{ $demoLeadEnquiry->mobile }} <br>
    <strong>Password:</strong> {{ 'archer@' . $user->id }}
</p>

<p style="font-size: 15px; color:#444;">
    Our team will contact you shortly to confirm the date and time of <strong>{{ $demoLeadEnquiry->kids_first_name }}</strong>’s trial class.
</p>

<p style="font-size: 15px; color:#444;">
    We look forward to welcoming <strong>{{ $demoLeadEnquiry->kids_first_name }}</strong> to Archer Chess Academy.
</p>

<br>

<p style="font-size: 15px; color:#444;">
    Sincerely, <br>
    <strong>Archer Chess Academy</strong>
</p>
@endcomponent
