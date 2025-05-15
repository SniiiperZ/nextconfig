<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nouvelle demande de devis PC</title>
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

        .highlight {
            background-color: #ec407a;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }

        .section {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #ec407a;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Nouvelle demande de devis PC</h1>
        <p>Budget estimé: <span style="font-weight: bold;">{{ $data['budget'] }}€</span></p>
    </div>

    <div class="content">
        <div class="field">
            <div class="field-label">Client:</div>
            <div class="field-value">{{ $data['name'] }} ({{ $data['email'] }})</div>
        </div>

        @if(isset($data['phone']) && $data['phone'])
        <div class="field">
            <div class="field-label">Téléphone:</div>
            <div class="field-value">{{ $data['phone'] }}</div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Type d'utilisation prévue</div>

            <div class="field">
                <div class="field-label">Usage principal:</div>
                <div class="field-value">
                    @switch($data['usage_type'])
                    @case('gaming')
                    Gaming
                    @break
                    @case('streaming')
                    Streaming
                    @break
                    @case('content_creation')
                    Création de contenu
                    @break
                    @case('office')
                    Bureautique
                    @break
                    @default
                    Autre
                    @endswitch
                </div>
            </div>

            <div class="field">
                <div class="field-label">Détails d'utilisation:</div>
                <div class="message-box">{{ $data['usage_details'] }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Préférences</div>

            @if(isset($data['preferred_brands']) && count($data['preferred_brands']) > 0)
            <div class="field">
                <div class="field-label">Marques préférées:</div>
                <div class="field-value">
                    @foreach($data['preferred_brands'] as $brand)
                    <span class="highlight">{{ $brand }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="field">
                <div class="field-label">Éclairage RGB:</div>
                <div class="field-value">
                    {{ isset($data['rgb_lighting']) && $data['rgb_lighting'] ? 'Oui' : 'Non' }}
                </div>
            </div>

            <div class="field">
                <div class="field-label">Délai souhaité:</div>
                <div class="field-value">
                    @switch($data['timeframe'])
                    @case('asap')
                    Dès que possible
                    @break
                    @case('1month')
                    D'ici 1 mois
                    @break
                    @case('3months')
                    D'ici 3 mois
                    @break
                    @default
                    Pas pressé
                    @endswitch
                </div>
            </div>
        </div>

        @if(isset($data['additional_notes']) && $data['additional_notes'])
        <div class="section">
            <div class="section-title">Notes additionnelles</div>
            <div class="message-box">{{ $data['additional_notes'] }}</div>
        </div>
        @endif

        <div class="field">
            <div class="field-label">Date de la demande:</div>
            <div class="field-value">{{ $data['created_at']->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Cette demande a été envoyée depuis le site NextConfig - {{ date('Y') }}</p>
        <p>Pour répondre, utilisez directement la fonction répondre de votre client mail.</p>
    </div>
</body>

</html>