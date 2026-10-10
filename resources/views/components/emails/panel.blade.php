@props(['title' => null, 'tone' => 'neutral'])
@php
    $background = $tone === 'warning' ? '#fff8eb' : '#f5f8f5';
    $border = $tone === 'warning' ? '#eedbb3' : '#dfe8df';
@endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; margin: 24px 0; border: 1px solid {{ $border }}; border-radius: 10px; border-collapse: separate; background-color: {{ $background }};">
    <tr>
        <td style="padding: 20px; font-family: Arial, Helvetica, sans-serif; color: #3c5143; font-size: 14px; line-height: 23px; overflow-wrap: break-word; word-wrap: break-word;">
            @if($title)
                <p style="margin: 0 0 14px; color: #627868; font-size: 10px; line-height: 16px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;">{{ $title }}</p>
            @endif
            {{ $slot }}
        </td>
    </tr>
</table>
