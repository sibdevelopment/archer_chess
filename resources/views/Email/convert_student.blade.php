@component('mail::message')

<p style="font-size: 16px; color: #555;">
    Dear {{ $student->first_name }},
</p>

<p style="font-size: 16px; color: #555;">
    This is to inform you that your chess class enrollment has been confirmed with <strong>Archer Chess Academy</strong>.
</p>

<p style="font-size: 16px; color: #555; margin-top: 20px;">
    <strong>Dashboard Credentials:</strong><br>
    LMS Link: <a href="https://archerchessacademy.com/student/login" target="_blank">https://archerchessacademy.com/student/login</a><br>
    Your Username: <strong>{{ $student->mobile }}</strong><br>
    Password: <strong>{{ $student->user->device_id }}</strong>
</p>

<p style="font-size: 16px; color: #555; margin-top: 20px;">
    <strong>Chesslang Credentials:</strong><br>
    Portal Link: <a href="https://app.chesslang.com/login" target="_blank">https://app.chesslang.com/login</a><br>
    Username: <strong>{{ $student->student_id}}</strong><br>
    Password: <strong>{{ $student->portal_password }}</strong>
</p>

<p style="font-size: 16px; color: #555; margin-top: 20px;">
    Kindly login & check all the details. If you have any queries, feel free to call us.
</p>

<p style="font-size: 16px; color: #555;">
    Thanks for choosing Archer!<br>
    Happy Learning! 😊
</p>

<p style="font-size: 14px; color: #777;">
    For tournament and academic information, join this group:<br>
    <a href="https://chat.whatsapp.com/JsGy4TaJazT7ufRYNG4AdA" target="_blank">https://chat.whatsapp.com/JsGy4TaJazT7ufRYNG4AdA</a>
</p>

<br>

Thanks,<br>
Support Team,<br>
<strong>Archer Chess Academy</strong>

@endcomponent

