@component('mail::message')

<h1 style="font-size: 24px; color: #333;">Hello, {{ $demoLeadEnquiry->kids_first_name }} {{ $demoLeadEnquiry->kids_last_name }}</h1>

<p style="font-size: 16px; color: #555;">
    We are pleased to inform you that your enquiry has been successfully verified and converted into a demo lead. Our team will contact you shortly to schedule your trial class.
</p>

<hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

<p style="font-size: 16px; color: #555;">
    If you have any questions or need further assistance, please do not hesitate to reach out to us.
</p>

<p style="font-size: 16px; color: #555;">
    Best regards,
</p>

<hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

<p style="font-size: 14px; color: #777;">
    If you have any questions or need assistance, feel free to contact us on WhatsApp at +91 9152734675.
</p>
<br /><br />
Thanks,<br>
Support Team,<br />
ArcherChess Academy<br />

@endcomponent