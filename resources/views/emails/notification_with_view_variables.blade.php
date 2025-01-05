<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body style="padding:0; margin:0;font-family:Verdana,Arial;line-height:150%;">
<table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="600" style="background-color:#343A40;padding-top:5px;border-bottom:1px solid #263758;">
            <a href="{{ $viewVariables['app_url'] }}" style="text-decoration:none;">
                <img height="40" width="auto" src="{{ $viewVariables['app_url'] }}/assets/images/logo-light.png" alt="" border="0" style="display: block; padding: 10px 0 13px 20px;" />
            </a>
        </td>
    </tr>
    <tr>
        <td style="color: #181818; background: #FFF; text-align: left; padding: 20px 40px 30px;">
            @if (!empty($viewVariables['html']))
                {!! $viewVariables['html'] !!}
            @else
                <div style="font-size:14px;">{!! $viewVariables['content'] !!}</div>
                @if (!empty($viewVariables['aLink']))
                    <div style="font-size:14px;"><a href="{{ $viewVariables['aLink'] }}" style="{!! $viewVariables['aStyle'] ?? 'font-size:16px;display:inline-block;margin-top:30px;margin-bottom:30px;padding:8px 24px;background-color:#263758;color:#fff;text-decoration:none;' !!}">{{ (!empty($viewVariables['aText'])) ? $viewVariables['aText'] : $viewVariables['aLink'] }}</a></div>
                @endif
                @if (!empty($viewVariables['content_2']))
                    <div style="font-size:14px;">{!! $viewVariables['content_2'] !!}</div>
                @endif
                @if (!empty($viewVariables['signer']))
                    <div style="font-size:14px;padding-top: 10px;">{!! $viewVariables['signer'] !!}</div>
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td style="background-color: #FFF; padding: 4px 0; vertical-align: middle;border-top:1px solid #263758;">
            @if (!empty($viewVariables['unsubscribe_link']))
                <p style="color: #5C5C5C; text-align: center; font-size: 11px; padding-top: 10px; padding-bottom: 0px; margin: 0;">
                    To stop receiving future notifications,
                    <a href="{{ $viewVariables['unsubscribe_link'] }}" style="color: #4a89dc; text-decoration: none">
                        unsubscribe here
                    </a>.
                </p>
            @endif
            <p style="font-weight: bold; text-align: center; color:#3c3c3c; font-size: 11px; padding-top: 10px; padding-bottom: 10px; margin: 0;">&copy; {{ date('Y') }} &bull; <a href="{{ $viewVariables['app_url'] }}" style="color: #263758; text-decoration: none">{{ $viewVariables['app_name'] }}</a></p>
        </td>
    </tr>
</table>
</body>
