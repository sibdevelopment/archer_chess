@props(['url'])
<tr>
    <td class="header">
        <a href="https://archerkids.technicul.com" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="https://archerkids.technicul.com/frontend/tcul_img/home/ArcherKids-logo.png" class="logo" style="width: 100px !important" alt="Archer Chess Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>