<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">

        <h2 style="color: #f0c040;">Hi {{ $lead->first_name }} {{ $lead->last_name }},</h2>

        <p>Thank you for your interest in our services.</p>

        <p>
            This is a reminder regarding your scheduled follow-up on
            <strong>{{ \Carbon\Carbon::parse($lead->date)->format('d M Y') }}</strong>
            @if($lead->time)
                at <strong>{{ \Carbon\Carbon::parse($lead->time)->format('h:i A') }}</strong>
            @endif.
        </p>

        @if($lead->comment)
            <p><strong>Note:</strong> {{ $lead->comment }}</p>
        @endif

        <p>Our team will be reaching out to you shortly. Feel free to reply to this email or call us anytime.</p>

        <p>Looking forward to hearing from you!</p>

        <p>
            Best regards,<br>
            <strong>{{ config('app.name') }}</strong><br>
            Sales Team
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

        <p style="font-size: 12px; color: #999;">
            This is an automated reminder. If you've already been contacted, please ignore this message.
        </p>

    </div>

</body>
</html>