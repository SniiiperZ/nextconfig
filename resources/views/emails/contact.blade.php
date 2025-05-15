<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background-color: #111;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            color: #ec407a;
        }

        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }

        .footer {
            background-color: #111;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }

        .field {
            margin-bottom: 15px;
        }

        .field-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #666;
        }

        .field-value {
            padding: 10px;
            background-color: #fff;
            border-left: 3px solid #ec407a;
        }

        .message-box {
            padding: 15px;
            background-color: #fff;
            border-left: 3px solid #ec407a;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Nouveau message de contact</h1>
        <p>Via le formulaire de contact de NextConfig</p>
    </div>

    <div class="content">
        <div class="field">
            <div class="field-label">Expéditeur:</div>
            <div class="field-value">{{ $data['name'] }} ({{ $data['email'] }})</div>
        </div>

        @if(isset($data['phone']) && $data['phone'])
        <div class="field">
            <div class="field-label">Téléphone:</div>
            <div class="field-value">{{ $data['phone'] }}</div>
        </div>
        @endif

        <div class="field">
            <div class="field-label">Sujet:</div>
            <div class="field-value">{{ $data['subject'] }}</div>
        </div>

        <div class="field">
            <div class="field-label">Message:</div>
            <div class="message-box">
                {{ $data['message'] }}
            </div>
        </div>

        <div class="field">
            <div class="field-label">Date d'envoi:</div>
            <div class="field-value">{{ $data['created_at']->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Ce message a été envoyé depuis le site NextConfig - {{ date('Y') }}</p>
        <p>Pour répondre, utilisez directement la fonction répondre de votre client mail.</p>
    </div>
</body>

</html>