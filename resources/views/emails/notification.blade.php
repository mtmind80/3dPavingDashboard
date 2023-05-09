<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body style="padding:0; margin:0;font-family:Verdana,Arial;line-height:150%;">
<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="600" style="background-color:#343A40;padding-top:5px;border-bottom:1px solid #263758;">
            <a href="{{ env('APP_URL') }}" style="text-decoration:none;">
                <img height="40" width="auto" src="{{ env('APP_URL') }}/assets/images/logo-light.png" alt="" border="0" style="display: block; padding: 10px 0 13px 20px;" />
            </a>
        </td>
    </tr>
    <tr>
        <td style="color: #181818; background: #FFF; text-align: left; padding: 20px 40px 30px;">
            @if (!empty($html))
                {!! $html !!}
            @else
                <div style="font-size:14px;">{!! $content !!}</div>
                @if (!empty($aLink))
                    <div style="font-size:14px;"><a href="{{ $aLink }}" style="{!! $aStyle ?? 'font-size:16px;display:inline-block;margin-top:30px;margin-bottom:30px;padding:8px 24px;background-color:#343A40;color:#fff;text-decoration:none;' !!}">{{ (!empty($aText)) ? $aText : $aLink }}</a></div>
                @endif
                @if (!empty($content_2))
                    <div style="font-size:14px;">{!! $content_2 !!}</div>
                @endif
                @if (!empty($signer))
                    <div style="font-size:14px;padding-top: 10px;">{!! $signer !!}</div>
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td style="background-color: #FFF; padding: 4px 0; vertical-align: middle;border-top:1px solid #263758;">
            <p style="font-weight: bold; text-align: center; color:#3c3c3c; font-size: 11px; padding-top: 10px; padding-bottom: 10px; margin: 0;">&copy; {{ date('Y') }} &bull; <a href="{{ env('APP_URL') }}" style="color: #263758; text-decoration: none">{{ env('APP_NAME') }}</a></p>
        </td>
    </tr>
</table>
</body>
