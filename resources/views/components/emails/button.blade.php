@props(['url'])
<table role="presentation" cellpadding="0" cellspacing="0" style="margin-top: 28px; border-collapse: separate; border-spacing: 0;">
    <tr>
        <td align="center" bgcolor="#196b43" style="background-color: #196b43; border: 1px solid #196b43; border-radius: 8px; mso-padding-alt: 15px 26px;">
            <a href="{{ $url }}" style="display: inline-block; padding: 15px 26px; border-radius: 8px; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 20px; font-weight: 700; text-align: center; text-decoration: none; mso-padding-alt: 0;">{{ $slot }}</a>
        </td>
    </tr>
</table>
