@props(['items'])
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
    @foreach($items as $label => $value)
        @if($value !== null && $value !== '')
            <tr>
                <td class="email-detail-label" width="34%" valign="top" style="width: 34%; padding: 8px 12px 8px 0; font-family: Arial, Helvetica, sans-serif; color: #687b6d; font-size: 13px; line-height: 21px;">{{ $label }}</td>
                <td width="66%" valign="top" style="width: 66%; padding: 8px 0; font-family: Arial, Helvetica, sans-serif; color: #293f31; font-size: 13px; line-height: 21px; font-weight: 600; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word;">{{ $value }}</td>
            </tr>
        @endif
    @endforeach
</table>
