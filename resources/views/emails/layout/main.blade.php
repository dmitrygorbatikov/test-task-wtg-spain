<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>{{ $appName }}</title>
</head>
<body style="margin: 0; width: 100%; height: 100%; background-color: #f8f8fb">
<table
    style="
        width: 100%;
        padding-top: 24px;
        padding-bottom: 24px;
        background-color: #f8f8fb;
      "
    cellspacing="0"
    cellpadding="0"
>
    <tbody>
    <tr>
        <td>
            <table
                border="0"
                cellpadding="0"
                cellspacing="0"
                style="
                max-width: 500px;
                width: 100%;
                margin: 24px auto 24px;
                border-spacing: 0;
                background-color: #fff !important;
                box-shadow: 0px 4px 8px 0px rgba(4, 11, 28, 0.16);
              "
            >
                <tbody>
                @component('components.emails.header', ['frontendUrl' => $frontendUrl]) @endcomponent
                {{ $content }}
                @component('components.emails.footer', [
                  'year' => $year,
                  'frontendUrl' => $frontendUrl,
           // TODO[emails]: Need remove all mentions of $frontendDomain in templates - because its not used
                  'frontendDomain' => $frontendDomain ?? '',
                  'withUnsubscribe' => $withUnsubscribe ?? false,
                  'unsubscribeLink' => $unsubscribeLink ?? null,
                ]) @endcomponent
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>

