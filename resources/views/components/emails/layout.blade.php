@props([
    'title',
    'heading',
    'eyebrow' => 'SRE · IFPE Igarassu',
    'preheader' => '',
    'actionUrl' => null,
    'actionLabel' => 'Acessar o SRE',
    'message' => null,
])
@php
    $ifpeLogo = $message instanceof \Illuminate\Mail\Message
        ? $message->embed(public_path('img/logo-instituto2.png'))
        : asset('img/logo-instituto2.png');
    $sreLogo = $message instanceof \Illuminate\Mail\Message
        ? $message->embed(public_path('img/logo-sre.png'))
        : asset('img/logo-sre.png');
@endphp
<!DOCTYPE html>
<html lang="pt-BR" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <title>{{ $title }}</title>
    <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
    <style>
        table { border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; }
        img { border: 0; outline: none; text-decoration: none; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
        @media only screen and (max-width: 620px) {
            .email-outer { padding: 16px 10px !important; }
            .email-padding { padding-left: 24px !important; padding-right: 24px !important; }
            .email-heading { font-size: 27px !important; line-height: 34px !important; }
            .email-ifpe-logo { width: 138px !important; height: auto !important; }
            .email-brand-caption { display: none !important; }
            .email-detail-label { width: 36% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #eef2ef; color: #263e31; font-family: Arial, Helvetica, sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <div style="display: none; font-size: 1px; line-height: 1px; color: #eef2ef; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all;">{{ $preheader }}</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#eef2ef" style="width: 100%; background-color: #eef2ef;">
        <tr>
            <td align="center" class="email-outer" style="padding: 40px 16px;">
                <!--[if mso]><table role="presentation" width="600" align="center" cellpadding="0" cellspacing="0"><tr><td><![endif]-->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" align="center" style="width: 100%; max-width: 600px; table-layout: fixed; background-color: #ffffff; border: 1px solid #dce5df; border-radius: 16px; border-collapse: separate; border-spacing: 0;">
                    <tr>
                        <td class="email-padding" style="padding: 26px 36px; border-bottom: 1px solid #e8eee9; border-radius: 16px 16px 0 0; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="55%" align="left" valign="middle">
                                        <img class="email-ifpe-logo" src="{{ $ifpeLogo }}" width="155" height="50" alt="IFPE — Instituto Federal de Pernambuco" style="display: block; width: 155px; height: 50px; max-width: 100%;">
                                    </td>
                                    <td width="45%" align="right" valign="middle">
                                        <table role="presentation" cellpadding="0" cellspacing="0" align="right">
                                            <tr>
                                                <td valign="middle" style="padding-right: 9px;">
                                                    <img src="{{ $sreLogo }}" width="38" height="37" alt="Logo SRE" style="display: block; width: 38px; height: 37px;">
                                                </td>
                                                <td valign="middle" style="font-family: Arial, Helvetica, sans-serif;">
                                                    <p style="margin: 0; font-size: 25px; line-height: 28px; font-weight: 700; letter-spacing: -1px; color: #193e2b;">SRE</p>
                                                    <p class="email-brand-caption" style="margin: 2px 0 0; font-size: 10px; line-height: 14px; color: #6a7d70;">Requerimento Estudantil</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#164c35" class="email-padding" style="padding: 32px 36px 34px; background-color: #164c35; border-bottom: 4px solid #67b780; font-family: Arial, Helvetica, sans-serif;">
                            <p style="margin: 0 0 12px; color: #b7ddc4; font-size: 11px; line-height: 16px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">{{ $eyebrow }}</p>
                            <h1 class="email-heading" style="margin: 0; color: #ffffff; font-size: 32px; line-height: 39px; font-weight: 700; letter-spacing: -0.7px;">{{ $heading }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-padding" style="padding: 32px 36px 36px; font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 25px; color: #4b5e51; overflow-wrap: break-word; word-wrap: break-word;">
                            {{ $slot }}
                            @if($actionUrl)
                                <x-emails.button :url="$actionUrl">{{ $actionLabel }}</x-emails.button>
                            @endif
                            @isset($afterAction)
                                {{ $afterAction }}
                            @endisset
                        </td>
                    </tr>
                    <tr>
                        <td class="email-padding" style="padding: 20px 36px; background-color: #f7f9f7; border-top: 1px solid #e8eee9; border-radius: 0 0 16px 16px; font-family: Arial, Helvetica, sans-serif;">
                            <p style="margin: 0; color: #52665a; font-size: 12px; line-height: 19px;">Precisa de ajuda? Entre em contato com o setor responsável no campus.</p>
                        </td>
                    </tr>
                </table>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;">
                    <tr>
                        <td align="center" style="padding: 24px 16px 0; font-family: Arial, Helvetica, sans-serif;">
                            <p style="margin: 0 0 4px; color: #51675a; font-size: 12px; line-height: 18px; font-weight: 700;">IFPE · Campus Igarassu</p>
                            <p style="margin: 0 0 10px; color: #6b7d71; font-size: 12px; line-height: 18px;">Sistema de Requerimento do Estudante</p>
                            <p style="margin: 0; color: #6b7d71; font-size: 11px; line-height: 18px;">© {{ date('Y') }} SRE. Mensagem automática. Por favor, não responda.</p>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
