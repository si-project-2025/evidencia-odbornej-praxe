<!DOCTYPE html>
<html>
<head>
    <title>Aktivácia účtu</title>
</head>
<body>
<h2>Dobrý deň {{ $user->name }},</h2>
<p>Ďakujeme za registráciu. Pre dokončenie registrácie a aktiváciu Vášho účtu si, prosím, nastavte heslo kliknutím na odkaz nižšie.</p>
<a href="{{ $url }}">Nastaviť heslo</a>
<p>Tento odkaz na nastavenie hesla vyprší o 60 minút.</p>
<p>Ak ste sa neregistrovali, môžete tento e-mail ignorovať.</p>
<br>
<p>S pozdravom,<br>Váš tím</p>
</body>
</html>
