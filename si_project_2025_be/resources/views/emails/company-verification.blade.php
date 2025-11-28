<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Overenie odbornej praxe – Evidencia odbornej praxe</title>
</head>
<body
    style="font-family: 'Inter', Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 30px;"
>
<div
    style="
        max-width: 520px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
      "
>
    <div style="background-color: #00A35F; padding: 24px 0; text-align: center;">
        <h1 style="color: #ffffff; font-size: 24px; margin: 0;">
            Evidencia odbornej praxe
        </h1>
    </div>

    <div style="padding: 36px 32px;">
        <h2 style="font-size: 20px; color: #111827; margin-bottom: 16px;">Overenie praxe študenta</h2>

        <p style="font-size: 16px; color: #374151; line-height: 1.6;">
            Dobrý deň, <br /><br />
            študent <strong>{{ $internship->user->name }} {{ $internship->user->surname }}</strong>
            uviedol Vašu firmu ako miesto vykonávania odbornej praxe.
        </p>

        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Firma:</strong> {{ $internship->company->name }}<br>
            <strong>Obdobie:</strong> {{ $internship->semester === 'Z' ? 'Zimný' : 'Letný' }} semester {{ $internship->year }}<br>
            <strong>Začiatok praxe:</strong> {{ $internship->start_at ? $internship->start_at->format('d.m.Y') : '—' }}<br>
            <strong>Koniec praxe:</strong> {{ $internship->end_at ? $internship->end_at->format('d.m.Y') : '—' }}
        </div>

        <p style="text-align: center; margin: 40px 0;">
            <a
                href="{{ $verificationUrl }}"
                target="_blank"
                style="
              background-color: #00A35F;
              color: #ffffff;
              padding: 12px 28px;
              border-radius: 8px;
              text-decoration: none;
              font-size: 16px;
              font-weight: 600;
              display: inline-block;
            "
            >
                Potvrdiť prax
            </a>
        </p>

        <p style="font-size: 14px; color: #6b7280; line-height: 1.5;">
            Tento odkaz je platný 7 dní. Ak ste tento email dostali omylom alebo študent nevykonal prax
            vo Vašej firme, ignorujte tento email.
        </p>

        <p style="font-size: 12px; color: #6b7280; line-height: 1.5;">
            Alternatívny odkaz:<br>
            <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
        </p>

        <p style="font-size: 14px; color: #6b7280; margin-top: 24px;">
            S pozdravom,<br />
            <strong>Tím Evidencie odbornej praxe</strong>
        </p>
    </div>

    <div
        style="
          background-color: #f9fafb;
          text-align: center;
          padding: 16px;
          font-size: 12px;
          color: #9ca3af;
        "
    >
        © {{ date('Y') }} Evidencia odbornej praxe. Všetky práva vyhradené.
    </div>
</div>
</body>
</html>

