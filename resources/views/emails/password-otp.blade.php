<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>رمز التحقق</title>
</head>
<body style="margin:0; padding:0; background:#F4F2EC; font-family: Arial, Helvetica, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F4F2EC; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid rgba(0,0,0,0.08);">
          <tr>
            <td style="background:#04342C; padding:28px 32px;">
              <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="width:36px; height:36px; background:#1D9E75; border-radius:10px; text-align:center; vertical-align:middle; color:#fff; font-size:16px; font-weight:bold;">H</td>
                  <td style="padding-right:10px; color:#fff; font-size:16px; font-weight:bold;">Hirfa</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:36px 32px;">
              <p style="margin:0 0 8px; font-size:18px; font-weight:bold; color:#111827;">رمز إعادة تعيين كلمة المرور</p>
              <p style="margin:0 0 24px; font-size:14px; color:#6B7280; line-height:1.7;">
                استخدم الرمز التالي لإتمام عملية إعادة تعيين كلمة المرور الخاصة بحسابك. هذا الرمز صالح لمدة محدودة فقط.
              </p>
              <div style="background:#F8F7F3; border:1px solid rgba(0,0,0,0.1); border-radius:12px; padding:20px; text-align:center; margin-bottom:24px;">
                <span style="font-size:32px; font-weight:bold; letter-spacing:8px; color:#04342C; direction:ltr; display:inline-block;">{{ $code }}</span>
              </div>
              <p style="margin:0; font-size:12px; color:#9CA3AF; line-height:1.7;">
                إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد بأمان.
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:20px 32px; border-top:1px solid rgba(0,0,0,0.06);">
              <p style="margin:0; font-size:11px; color:#9CA3AF;">© {{ date('Y') }} Hirfa Platform. All rights reserved.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
