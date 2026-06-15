@component('mail::message')

    <p style="font-size: 16px; color: #555;"><strong>Dear Parents/Guardians,</strong></p>

    <p style="font-size: 16px; color: #555;">
        We hope this message finds you well.
    </p>

    <p style="font-size: 16px; color: #555;">
        This is a <strong>courteous follow-up</strong> regarding the pending fee for <strong>{{ $student->first_name }}</strong>’s upcoming chess module, scheduled to begin on <strong>{{ toIndianDate($date) }}</strong>. As of today, we have not yet received the payment.
    </p>

    <p style="font-size: 16px; color: #555;">
        To ensure your child's uninterrupted participation, we kindly request that the payment be completed <strong>at your earliest convenience</strong>. This will allow our administrative team to update records and confirm enrollment in a timely manner.
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
        We sincerely thank you for your attention to this matter and for being a valued part of the Archer Chess Academy community.
    </p>

    <br>

    <p style="font-size: 16px; color: #555;">
        <strong>Warm regards,</strong><br>
        <strong>Archer Chess Academy</strong>
    </p>

@endcomponent
