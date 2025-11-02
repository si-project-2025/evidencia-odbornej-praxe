<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Overenie odbornej praxe</h2>

    <p>Dobrý deň,</p>

    <p>študent <strong>{{ $internship->user->name }} {{ $internship->user->surname }}</strong> uviedol Vašu firmu ako miesto vykonávania odbornej praxe.</p>

    <div class="info">
        <strong>Firma:</strong> {{ $internship->company->name }}<br>
        <strong>Obdobie:</strong> {{ $internship->semester === 'Z' ? 'Zimný' : 'Letný' }} semester {{ $internship->year }}<br>
        <strong>Počet hodín:</strong> {{ $internship->hours_total }}<br>
        <strong>Koniec praxe:</strong> {{ $internship->end_at ? $internship->end_at->format('d.m.Y') : '—' }}
    </div>

    <p>Pre potvrdenie tejto praxe kliknite na tlačidlo nižšie:</p>

    <a href="{{ $verificationUrl }}" class="button">Potvrdiť prax</a>

    <p style="font-size: 12px; color: #666;">
        Tento odkaz je platný 7 dní. Ak ste tento email nedostali omylom alebo študent nevykonal prax vo Vašej firme, ignorujte tento email.
    </p>

    <p style="font-size: 12px; color: #666;">
        Alternatívny odkaz:<br>
        <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
    </p>
</div>
</body>
</html>
