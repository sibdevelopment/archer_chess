@component('mail::message')
<p style="font-size: 16px; color: #555;"><strong>Dear Parents/Guardians,</strong></p>

<p style="font-size: 16px; color: #555;">
    We hope this message finds you well.
</p>

<p style="font-size: 16px; color: #555;">
    This is a <strong>courteous reminder</strong> that
    <strong>{{ $student->first_name }}</strong> has successfully completed
    the current module of their chess classes today.
    @if(!empty($next_date))
        The next module is scheduled to commence on
        <strong>{{ \Carbon\Carbon::parse($next_date)->format('d M, Y') }}</strong>.
    @else
        The next module start date will be shared shortly.
    @endif
</p>

<p style="font-size: 16px; color: #555;">
    To ensure a smooth and uninterrupted learning experience, we kindly request that the fee for the upcoming module be
    paid <strong>at least one day prior to the next class</strong>. This allows our team sufficient time to update
    records and confirm enrollment.
</p>

<p style="font-size: 16px; color: #555;">
    Please use the student portal to make the payment:
</p>

<p style="font-size: 16px; color: #555;">
    🔗 <a href="https://archerchessacademy.com/student/login">https://archerchessacademy.com/student/login</a><br>
    <strong>Username:</strong> {{ $student->mobile }}<br>
    <strong>Password:</strong> {{ 'archer@' . $student->user->id }}
</p>

<p style="font-size: 16px; color: #555;">
    If payment has already been completed, kindly disregard this message.
</p>

<p style="font-size: 16px; color: #555;">
    Thank you for your prompt attention and continued support. We are grateful to have you as a valued part of the
    Archer Chess Academy community.
</p>

<br>
<p style="font-size: 16px; color: #555;">
    <strong>Warm regards,</strong><br>
    <strong>Archer Chess Academy</strong>
</p>
@endcomponent
