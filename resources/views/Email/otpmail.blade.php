@component('mail::message')
<p style="border-collapse: collapse; table-layout: fixed; width: 1000px;">
    Hi,<br />
    You have initiated a new registration on Checkmark Clothing Website.
    <br />
    Please verify your email using the OTP below.
</p>

<table style="border-collapse: collapse; table-layout: fixed; width: 1000px;">
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>Email-Id</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $email !!}</b></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>OTP</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $otp !!}</b></td>
        </tr>
    </tbody>
</table>
<br />

<hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

<p style="font-size: 14px; color: #777;">
    If you have any questions or need assistance, feel free to contact us on WhatsApp at +91 9152734675.
</p>
<br /><br />
Thanks,<br>
Support Team,<br />
ArcherChess Academy<br />

@endcomponent