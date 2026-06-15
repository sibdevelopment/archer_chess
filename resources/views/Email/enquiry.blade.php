@component('mail::message')
<p style="border-collapse: collapse; table-layout: fixed; width: 1000px;">
    Hi {{ $first_name }} {{ $last_name }},
    <br />
    Thank you for your enquiry on ArcherKids Academy Website.
    <br />
    We have received your enquiry with the following details:
</p>

<table style="border-collapse: collapse; table-layout: fixed; width: 1000px;">
    <tbody>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>First Name</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $first_name !!}</b></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>Last Name</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $last_name !!}</b></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>Email</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $email !!}</b></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>Subject</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $subject !!}</b></td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 10px; width: 100px; overflow: hidden;"><b>Description</b></td>
            <td style="border: 1px solid black; padding: 10px;"><b>&nbsp;&nbsp;{!! $description !!}</b></td>
        </tr>
    </tbody>
</table>
<br />

<p style="font-size: 14px; color: #777;">
    If you have any questions or need assistance, feel free to contact us on WhatsApp at +91 9152734675.
</p>
<br /><br />
Thanks,<br>
Support Team,<br />
ArcherChess Academy<br />

@endcomponent