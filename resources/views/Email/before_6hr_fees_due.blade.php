@component('mail::message')

<p style="font-size: 16px; color: #555;"><em>Dear Parents/Guardians,</em></p>

<p style="font-size: 16px; color: #555;">
    We hope this message finds you well.
</p>

<p style="font-size: 16px; color: #555;">
    This is a gentle reminder that the fee for {{ $student->first_name }}'s upcoming module is still pending. To confirm their spot, please complete the payment by {{ toIndianDate($fee_due->end_date) }}. We look forward to seeing them in the module!
</p>
<p style="font-size: 16px; color: #555;">
    To ensure that your child’s participation in the upcoming module is not affected, we kindly request that the payment be completed <em>at your earliest convenience</em>.
</p>

<p style="font-size: 16px; color: #555;">
    Please use the student portal to make the payment:
</p>

<p style="font-size: 16px; color: #555;">
    🔗 <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    <em>Username:</em> {{ $student->mobile }}<br>
    <em>Password:</em> {{ 'archer@' . $student->user->id }}
</p>

<p style="font-size: 16px; color: #555;">
    If the payment has already been made, kindly disregard this message.
</p>

<p style="font-size: 16px; color: #555;">
    We appreciate your prompt attention to this matter and thank you for being a valued part of the Archer Chess Academy community.
</p>

<br>

<p style="font-size: 16px; color: #555;">
    <em>Sincerely,</em><br>
    <em>Archer Chess Academy</em>
</p>

@endcomponent
