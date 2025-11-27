<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SkyraMart</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #10b981;
            margin: 0;
            font-size: 32px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .benefit-item {
            display: flex;
            align-items: start;
            margin: 15px 0;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .benefit-icon {
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .contact-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 SkyraMart</h1>
            <p>Your Trusted Shopping Partner</p>
        </div>

        <div class="content">
            @if($template)
                <!-- Greeting -->
                <div class="welcome-box">
                    {!! nl2br(e($template->replaceVariables($template->greeting_text ?? '', $customer))) !!}
                </div>

                <!-- Account Details -->
                @if($template->account_details_title)
                <div class="info-box">
                    <p><strong>{{ $template->replaceVariables($template->account_details_title, $customer) }}</strong></p>
                    <p>📧 Email: {{ $customer->email }}</p>
                    @if($customer->phone_number)
                    <p>📞 Phone: {{ $customer->phone_number }}</p>
                    @endif
                    <p>🎁 Loyalty Points: {{ $customer->loyalty_points }} points</p>
                </div>
                @endif

                <!-- Benefits -->
                @if($template->benefits_title)
                <div class="benefits">
                    <h3 style="color: #10b981;">{{ $template->replaceVariables($template->benefits_title, $customer) }}</h3>
                    
                    @php
                        $benefitsList = json_decode($template->benefits_list, true);
                    @endphp

                    @if(is_array($benefitsList))
                        @foreach($benefitsList as $benefit)
                        <div class="benefit-item">
                            <div class="benefit-icon">{{ $benefit['icon'] }}</div>
                            <div>
                                <strong>{{ $benefit['title'] }}</strong>
                                <p>{{ $benefit['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div style="white-space: pre-wrap;">{!! nl2br(e($template->replaceVariables($template->benefits_list ?? '', $customer))) !!}</div>
                    @endif
                </div>
                @endif

                <!-- Contact Info -->
                @if($template->contact_info)
                <div class="contact-info">
                    <p><strong>📞 Need Help?</strong></p>
                    {!! nl2br(e($template->replaceVariables($template->contact_info, $customer))) !!}
                </div>
                @endif

                <!-- Footer Text -->
                @if($template->footer_text)
                <p style="margin-top: 30px;">{!! nl2br(e($template->replaceVariables($template->footer_text, $customer))) !!}</p>
                @endif

            @else
                <p>Welcome to SkyraMart! Your account has been created successfully.</p>
            @endif
        </div>

        <div class="footer">
            @if($template && $template->store_branding)
                <p><strong>{{ $template->replaceVariables($template->store_branding, $customer) }}</strong></p>
            @else
                <p><strong>SkyraMart</strong> - Your Trusted Store</p>
            @endif
            <p>This is an automated message. Please do not reply to this email.</p>
            <p style="margin-top: 10px; font-size: 12px; color: #999;">
                © {{ date('Y') }} SkyraMart. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>