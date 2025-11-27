<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SkyraMart</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #e8f1ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .email-wrapper {
            padding: 40px 15px;
        }

        .email-container {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.07);
            border: 1px solid #e2e9f5;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            background: #4db2ff;
            padding: 25px 30px;
        }

        /* Text */
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #111111;
            margin-bottom: 12px;
        }

        .message {
            font-size: 15px;
            color: #444444;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        /* BUTTON */
        .reset-button {
            display: inline-block;
            padding: 14px 42px;
            background: #4db2ff;
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
        }

        .expiry-notice {
            background: #e1efff;
            border-left: 4px solid #4db2ff;
            padding: 15px 18px;
            margin-top: 15px;
            border-radius: 6px;
        }

        .alternative-link {
            background: #f5f8ff;
            padding: 18px 20px;
            border-radius: 6px;
            margin-top: 25px;
            border: 1px solid #d7e3f8;
        }

        .divider {
            height: 1px;
            background-color: #e6ebf5;
            margin: 32px 0;
        }

        .footer {
            background: #f0f4fb;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #dbe3f3;
        }

        @media (max-width: 600px) {
            .header-title {
                font-size: 20px !important;
            }
        }
    </style>
</head>

<body>
<div class="email-wrapper">
<div class="email-container">

    <!-- HEADER -->
<table role="presentation" class="header-table">
    <tr style="vertical-align: middle;">

        <!-- LOGO KIRI (UKURAN DIBESARKAN) -->
        <td style="width: 80px;">
            <img src="https://i.ibb.co/SX1dyCL8/Skyra-L1.png"
                 width="64"
                 style="display:block;">
        </td>

        <!-- SPASI -->
        <td style="width:10px;"></td>

        <!-- TULISAN RESET PASSWORD (TENGAH) -->
        <td style="text-align:center;">
            <h1 class="header-title" style="margin:0;color:white;font-size:26px;font-weight:700;">
                Reset Password
            </h1>
        </td>

        <!-- Spacer kanan -->
        <td style="width:80px;"></td>
    </tr>
</table>


    <!-- CONTENT -->
    <div class="content" style="padding:40px 40px 35px 40px;">

        <div class="greeting">Hello, {{ $user->name }},</div>

        <div class="message">
            We received a request to reset the password for your SkyraMart account.
            To continue, please click the button below.
        </div>

        <div style="text-align:center; margin:30px 0;">
            <a href="{{ $url }}" class="reset-button">Reset Password</a>
        </div>

        <div class="expiry-notice">
            <p>This reset link will expire in <strong>60 minutes</strong>.</p>
        </div>

        <div class="alternative-link">
            <p>If the button above does not work, copy and paste the link below into your browser:</p>
            <a href="{{ $url }}">{{ $url }}</a>
        </div>

        <div class="divider"></div>

        <div class="security-notice" style="font-size:13px;color:#777;line-height:1.6;">
            If you did not request this, please ignore this email.
            Your account will remain secure and unchanged.
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p class="brand" style="color:#4db2ff;font-weight:700;">SkyraMart</p>
        <p>&copy; {{ date('Y') }} SkyraMart. All rights reserved.</p>
    </div>

</div>
</div>
</body>
</html>
