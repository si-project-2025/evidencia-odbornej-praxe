<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zmena stavu praxe – Evidencia odbornej praxe</title>
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
        <h2 style="font-size: 20px; color: #111827; margin-bottom: 16px;">Zmena stavu praxe</h2>

        <p style="font-size: 16px; color: #374151; line-height: 1.6;">
            Dobrý deň, <strong>{{ $name }}</strong>,<br><br>
            v systéme <strong>Evidencia odbornej praxe</strong>
            bol aktualizovaný stav praxe.
        </p>

        <p style="font-size: 16px; color: #111827; margin-top: 12px;">
            <strong>Nový stav:</strong> {{ $status }}
        </p>

        @isset($student)
            <p style="font-size: 16px; color: #111827; margin-top: 8px;">
                <strong>Študent:</strong> {{ $student }}
            </p>
        @endisset

        @isset($company)
            <p style="font-size: 16px; color: #111827; margin-top: 8px;">
                <strong>Firma:</strong> {{ $company }}
            </p>
        @endisset

        <p style="text-align: center; margin: 40px 0;">
            <a
                href="{{ $url }}"
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
                Zobraziť prax
            </a>
        </p>

        <p style="font-size: 14px; color: #6b7280; line-height: 1.5;">
            Tento e-mail bol vygenerovaný automaticky na základe zmeny stavu praxe.
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
