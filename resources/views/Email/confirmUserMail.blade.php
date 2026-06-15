@component('mail::message')
<h1 style="font-size: 24px; color: #333;">Email Verification Successful</h1>

<p style="font-size: 16px; color: #555;">Hello {{ $user->kids_first_name }},</p>

<p style="font-size: 16px; color: #555;">
    We are pleased to inform you that your email address ({{ $user->email }}) has been successfully verified. Thank you for taking the time to complete this important step.
</p>

<p style="font-size: 16px; color: #555;">
    Your verified email ensures that you receive all important updates and communications from ArcherChess Academy.
</p>

<p style="font-size: 16px; color: #555;">
    If you have any questions or need further assistance, please do not hesitate to reach out to us.
</p>

<p style="font-size: 16px; color: #555;">
    Thank you for being a valued member of the ArcherChess Academy community.
</p>

<br />

<p style="font-size: 14px; color: #777;">
    If you have any questions or need assistance, feel free to contact us on WhatsApp at +91 9152734675.
</p>
<br /><br />
Thanks,<br>
Support Team,<br />
ArcherChess Academy<br />
@endcomponent