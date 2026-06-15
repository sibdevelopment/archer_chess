@component('mail::message')

<p style="font-size: 16px; color: #555;">
    <strong>Dear {{ $demolead->user->first_name }},</strong>
</p>

<p style="font-size: 16px; color: #555;">
    Your Chess demo has been scheduled on 
    <strong>{{ $istDateTime }}</strong> IST with Archer Chess Academy.<br>
    Your local timing: <strong>{{ $studentDateTime }}</strong>.
</p>


<p style="font-size: 16px; color: #555;">
    Kindly join on time. If you wish to cancel due to any reason, please inform us at least 30 minutes in advance so we can offer this slot to other students.
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Coach:</strong> {{ $demosession->coach->user->first_name }} {{ $demosession->coach->user->last_name }}<br>
</p>

<p style="font-size: 16px; color: #555;">
    For joining the demo, kindly open the student dashboard and click on the <strong>Join Demo</strong> button.<br>
    If you have any queries, contact us at +91 76270 86196.
</p>

<p style="font-size: 16px; color: #555;">
    <strong>Credentials:</strong><br>
    URL: <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    Username: {{ $demolead->user->mobile }}<br>
    Password: {{ 'archer@' . $demolead->user->id }}
</p>

<p style="font-size: 16px; color: #555;">
    <em>Note:</em> The demo session will be conducted on Zoom. Please download the Zoom app on your device in advance.
</p>

<br>

<p style="font-size: 16px; color: #555;">
    <em>Thanks & Regards,</em><br>
    <em>Archer Chess Academy</em>
</p>

@endcomponent
