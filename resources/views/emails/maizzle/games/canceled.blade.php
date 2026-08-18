<!DOCTYPE html>
@php
$appUrl = env('APP_FRONTEND_URL');
@endphp
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings xmlns:o="urn:schemas-microsoft-com:office:office">
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <style>
    td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
  </style>
  <![endif]-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" media="screen">
  <style>
    @media (max-width: 600px) {
      .sm-p-6 {
        padding: 24px !important
      }
      .sm-px-4 {
        padding-left: 16px !important;
        padding-right: 16px !important
      }
    }
  </style>
</head>
<body style="margin: 0; width: 100%; padding: 0; -webkit-font-smoothing: antialiased; word-break: break-word">
  <div role="article" aria-roledescription="email" aria-label lang="en">
    <div class="sm-px-4" style="background-color: #f8fafc; font-family: Inter, ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif">
      <table align="center" style="margin: 0 auto" cellpadding="0" cellspacing="0" role="none">
        <tr>
          <td style="width: 700px; max-width: 100%">
            <div role="separator" style="line-height: 24px">&zwj;</div>
            <table style="width: 100%" cellpadding="0" cellspacing="0" role="none">
              <tr>
                <td class="sm-p-6" style="border-radius: 8px; background-color: #fffffe; padding: 24px 36px; border: 1px solid #e2e8f0">
                  <a href="{{ $appUrl }}">
                    <img src="{{asset('logo.png')}}" width="110" alt style="max-width: 100%; vertical-align: middle">
                  </a>
                  <div role="separator" style="line-height: 24px">&zwj;</div>
                  <div>
                    <h1 style="margin: 0; font-size: 24px; line-height: 32px; font-weight: 600; color: #264054">
                      {{ $greetingName ? __('games.mail.greeting', ['name' => $greetingName]) : __('games.mail.greeting_no_name') }}
                    </h1>
                    <p style="font-size: 16px; line-height: 24px; color: #366c93; margin: 24px 0 0">
                      {{ __('games.mail.canceled_intro') }}
                    </p>
                    @if ($reason)
                    <p style="font-size: 16px; line-height: 24px; color: #366c93; margin: 24px 0 0">
                      {{ __('games.mail.reason') }}: {{ $reason }}
                    </p>
                    @endif
                    <h2 style="font-size: 18px; line-height: 28px; font-weight: 600; color: #284a64; margin-top: 24px; margin-bottom: 0">
                      {{ __('games.mail.details') }}
                    </h2>
                    <table style="width: 100%; border-width: 1px; border-color: #e5e7eb; margin-top: 24px; margin-bottom: 0" cellpadding="0" cellspacing="0" role="none">
                      <tbody>
                        <tr>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px; color: #64748b">{{ __('games.mail.sport') }}</td>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px">{{ $game->courtType->name }}</td>
                        </tr>
                        <tr>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px; color: #64748b">{{ __('games.mail.date') }}</td>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px">{{ $game->start_time->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px; color: #64748b">{{ __('games.mail.time') }}</td>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px">{{ $game->start_time->format('H:i') }} - {{ $game->end_time->format('H:i') }}</td>
                        </tr>
                        @if ($game->court)
                        <tr>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px; color: #64748b">{{ __('games.mail.court') }}</td>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px">{{ $game->court->name }}</td>
                        </tr>
                        @endif
                        <tr>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px; color: #64748b">{{ __('games.mail.price') }}</td>
                          <td style="border-bottom-width: 1px; border-color: #e5e7eb; padding: 8px">{{ $game->price_with_vat }} €</td>
                        </tr>
                      </tbody>
                    </table> @if ($refundedAmount > 0)
                    <p style="font-size: 16px; line-height: 24px; color: #366c93; margin: 24px 0 0">
                      💰 {{ __('games.mail.canceled_refund', ['amount' => number_format($refundedAmount, 2, ',', ' ')]) }}
                    </p>
                    @endif
                  </div>
                  <div role="separator" style="line-height: 24px">&zwj;</div>
                  <p style="margin: 0; font-size: 16px; line-height: 24px; color: #2f5c7e">
                    {{ __('emails.signature') }}<br>
                    <span style="font-weight: 600">WIDEN arena</span>
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>