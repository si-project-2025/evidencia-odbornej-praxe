<!DOCTYPE html>
<html lang="sk-SK">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>
    </title>
    <style>
        body {
            text-align: justify;
            font-family: 'Times New Roman';
            font-size: 12pt
        }

        p {
            margin: 3pt 0pt 0pt
        }

        li {
            margin-top: 3pt;
            margin-bottom: 0pt
        }

        .Footer {
            margin-top: 0pt;
            margin-bottom: 0pt;
            text-align: justify;
            line-height: normal;
            font-family: 'Times New Roman';
            font-size: 12pt
        }

        .Header {
            margin-top: 0pt;
            margin-bottom: 0pt;
            text-align: justify;
            line-height: normal;
            font-family: 'Times New Roman';
            font-size: 12pt
        }

        .ListParagraph {
            margin-top: 3pt;
            margin-left: 36pt;
            margin-bottom: 0pt;
            text-align: justify;
            line-height: normal;
            font-family: 'Times New Roman';
            font-size: 12pt
        }

        span.HlavikaChar {
            font-family: 'Times New Roman';
            font-size: 12pt
        }

        span.Hyperlink {
            text-decoration: underline;
            color: #0000ff
        }

        span.PtaChar {
            font-family: 'Times New Roman';
            font-size: 12pt
        }

        span.UnresolvedMention {
            color: #605e5c;
            background-color: #e1dfdd
        }

        .awlist1 {
            list-style: none;
            counter-reset: awlistcounter1_1
        }

        .awlist1>li:before {
            content: '1.' counter(awlistcounter1_1);
            counter-increment: awlistcounter1_1
        }

        .awlist2 {
            list-style: none;
            counter-reset: awlistcounter4_0
        }

        .awlist2>li:before {
            content: '-';
            counter-increment: awlistcounter4_0
        }

        @media (max-width: 900px) {
            img {
                max-width: 100%;
                height: auto;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td,
            th {
                padding: 8px;
                text-align: left;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
<div>
    <p style="margin-top:0pt; text-align:center; font-size:11pt;"><strong><span style="font-family:Calibri; text-transform:uppercase;">Dohoda o</span></strong><strong><span style="font-family:Calibri; text-transform:uppercase;">&nbsp;</span></strong><strong><span style="font-family:Calibri; text-transform:uppercase;">odbornej praxi &scaron;tudenta</span></strong></p>
    <p style="margin-top:0pt; text-align:center; font-size:11pt;"><span style="font-family:Calibri;">uzatvoren&aacute; v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">zmysle &sect; 51 Občianskeho z&aacute;konn&iacute;ka a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">Z&aacute;kona č. 131/2002 Z.z. o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">vysok&yacute;ch &scaron;kol&aacute;ch</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><strong><span style="font-family:Calibri;">Univerzita Kon&scaron;tant&iacute;na Filozofa v Nitre</span></strong></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">Fakulta pr&iacute;rodn&yacute;ch vied a informatiky</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">Trieda A. Hlinku 1, 949 01 Nitra</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">v zast&uacute;pen&iacute; prof. RNDr. Franti&scaron;ek Petrovič, PhD., MBA &ndash; dekan fakulty</span></p>
    <p style="margin-top:0pt; text-align:left;"><span style="font-family:Calibri; font-size:11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri; font-size:11pt;">e-mail:&nbsp;</span><a href="mailto:dfpvai@ukf.sk" style="text-decoration:none;"><span class="Hyperlink" style="font-family:Calibri; font-size:11pt;">dfpvai@ukf.sk</span></a><span style="font-family:Calibri; font-size:11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri; font-size:11pt;">tel. 037/6408 555</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><strong><span style="font-family:Calibri;">Poskytovateľ odbornej praxe (organiz&aacute;cia, resp. in&scaron;tit&uacute;cia)</span></strong></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri; color:#ff0000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">Pln&yacute; n&aacute;zov a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">adresa {{ $internship->company->name }}</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">zast&uacute;pen&iacute; ........................................................................................... (meno, poz&iacute;cia)</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:left; font-size:11pt;"><strong><span style="font-family:Calibri;">&Scaron;tudent:</span></strong></p>
    <p style="margin-top:0pt; text-indent:28.35pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">Meno a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">priezvisko:</span><span style="width:29.05pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;"></span></p>
    <p style="margin-top:0pt; text-indent:28.35pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">Adresa trval&eacute;ho bydliska:</span><span style="width:0.82pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">adresa</span></p>
    <p style="margin-top:0pt; text-indent:28.35pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">Kontakt &scaron;tudenta FPVaI UKF v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">Nitre:</span><span style="width:23.56pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">e-mail, tel.</span></p>
    <p style="margin-top:0pt; text-indent:28.35pt; text-align:left; font-size:11pt;"><span style="font-family:Calibri;">&Scaron;tudijn&yacute; program:</span><span style="width:33.12pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">aplikovan&aacute; informatika</span></p>
    <p style="margin-top:0pt; text-align:left; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:left; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">uzatv&aacute;raj&uacute; t&uacute;to dohodu o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">odbornej praxi &scaron;tudenta.</span></p>
    <p style="margin-top:0pt; text-align:left; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:center; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">I. Predmet dohody</span></strong></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">Predmetom tejto dohody je vykonanie odbornej praxe &scaron;tudenta v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">rozsahu 150 hod&iacute;n, v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">term&iacute;ne od ..................................... do .................................. bezodplatne.</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:center; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">II. Pr&aacute;va a</span></strong><strong><span style="font-family:Calibri;">&nbsp;</span></strong><strong><span style="font-family:Calibri;">povinnosti &uacute;častn&iacute;kov dohody</span></strong></p>
    <p style="margin-top:0pt; text-align:center; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">&nbsp;</span></strong></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">1. Fakulta pr&iacute;rodn&yacute;ch vied a</span></strong><strong><span style="font-family:Calibri;">&nbsp;</span></strong><strong><span style="font-family:Calibri;">informatiky Univerzity Kon&scaron;tant&iacute;na Filozofa v</span></strong><strong><span style="font-family:Calibri;">&nbsp;</span></strong><strong><span style="font-family:Calibri;">Nitre:</span></strong></p>
    <ol class="awlist1" style="margin:0pt; padding-left:0pt;">
        <li style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; text-align:left; line-height:115%; font-family:Calibri; font-size:11pt;"><span style="width:4.07pt; font:7pt 'Times New Roman'; display:inline-block;">&nbsp;&nbsp;&nbsp;</span>Pover&iacute; svojho zamestnanca: Mgr. Dominik Halvon&iacute;k, PhD. (ďalej garant odbornej praxe) garanciou odbornej praxe.</li>
    </ol>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">1.2 Prostredn&iacute;ctvom garanta odbornej praxe:</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">a)&nbsp;</span><span style="width:9.39pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">poskytne &scaron;tudentovi:</span></p>
    <ul class="awlist2" style="margin:0pt; padding-left:0pt;">
        <li class="ListParagraph" style="margin-top:0pt; margin-left:48pt; text-indent:-18pt; line-height:115%; font-family:Calibri; font-size:11pt;"><span style="width:14.63pt; font:7pt 'Times New Roman'; display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>inform&aacute;cie o&nbsp;organiz&aacute;cii praxe, o&nbsp;podmienkach dojednania dohody o&nbsp;odbornej praxi, o&nbsp;obsahovom zameran&iacute; odbornej praxe a&nbsp;o&nbsp;požiadavk&aacute;ch na obsahov&uacute; n&aacute;plň spr&aacute;vy z&nbsp;odbornej praxe,</li>
        <li class="ListParagraph" style="margin-top:0pt; margin-left:48pt; text-indent:-18pt; line-height:115%; font-family:Calibri; font-size:11pt;"><span style="width:14.63pt; font:7pt 'Times New Roman'; display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>n&aacute;vrh dohody o&nbsp;odbornej praxi &scaron;tudenta,</li>
    </ul>
    <p style="margin-top:0pt; margin-left:35.45pt; text-indent:-35.45pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">b)&nbsp;</span><span style="width:8.93pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">rozhodne o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">udelen&iacute; hodnotenia &bdquo;ABS&ldquo; (absolvoval) &scaron;tudentovi na z&aacute;klade dokladu &bdquo;V&yacute;kaz o vykonanej odbornej praxi&ldquo;, vydan&eacute;ho poskytovateľom odbornej praxe a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">na z&aacute;klade &scaron;tudentom vypracovanej spr&aacute;vy o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">odbornej praxi, ktorej s&uacute;časťou je verejn&aacute; obhajoba v&yacute;sledkov odbornej praxe,</span></p>
    <p style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">c)&nbsp;</span><span style="width:10.01pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">spravuje vyplnen&uacute; a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">&uacute;častn&iacute;kmi podp&iacute;san&uacute; dohodu o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">odbornej praxi.</span></p>
    <p style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">2. Poskytovateľ odbornej praxe:</span></strong></p>
    <p style="margin-top:0pt; margin-left:21.3pt; text-indent:-21.3pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">2.1 pover&iacute; svojho zamestnanca (t&uacute;tor - zodpovedn&yacute; za odborn&uacute; prax v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">organiz&aacute;cii) ....................................................................., ktor&yacute; bude dohliadať na dodržiavanie dohody o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">odbornej praxi, plnenie obsahovej n&aacute;plne odbornej praxe a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">bude n&aacute;pomocn&yacute; pri z&iacute;skavan&iacute; potrebn&yacute;ch &uacute;dajov pre vypracovanie spr&aacute;vy z</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">odbornej praxe,&nbsp;</span></p>
    <p style="margin-top:0pt; margin-left:21.3pt; text-indent:-21.3pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">2.2 na začiatku praxe vykon&aacute; poučenie o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">bezpečnosti a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">ochrane zdravia pri pr&aacute;ci v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">zmysle platn&yacute;ch predpisov,</span></p>
    <p style="margin-top:0pt; margin-left:21.3pt; text-indent:-21.3pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">2.3 vzniknut&eacute; organizačn&eacute; probl&eacute;my s&uacute;visiace s</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">plnen&iacute;m dohody rie&scaron;i spolu s</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">garantom odbornej praxe,</span></p>
    <p style="margin-top:0pt; margin-left:21.3pt; text-indent:-21.3pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">2.4&nbsp;</span><span style="width:4.89pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">po ukončen&iacute; odbornej praxe vyd&aacute; &scaron;tudentovi &bdquo;V&yacute;kaz o vykonanej odbornej praxi&ldquo;, ktor&yacute; obsahuje popis vykon&aacute;van&yacute;ch činnost&iacute; a stručn&eacute; hodnotenie &scaron;tudenta a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">je jedn&yacute;m z</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">predpokladov &uacute;spe&scaron;n&eacute;ho ukončenia predmetu Odborn&aacute; prax,</span></p>
    <p style="margin-top:0pt; margin-left:21.3pt; text-indent:-21.3pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">2.5&nbsp;</span><span style="width:4.89pt; text-indent:0pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">umožn&iacute; garantovi odbornej praxe a</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">garantovi &scaron;tudijn&eacute;ho predmetu kontrolu &scaron;tudentom plnen&yacute;ch &uacute;loh.</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:left; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">3. &Scaron;tudent FPVaI UKF v</span></strong><strong><span style="font-family:Calibri;">&nbsp;</span></strong><strong><span style="font-family:Calibri;">Nitre:</span></strong></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">3.1 osobne zabezpeč&iacute; podp&iacute;sanie tejto dohody o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">odbornej praxi &scaron;tudenta,</span></p>
    <p style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">3.2 zodpovedne vykon&aacute;va činnosti pridelen&eacute; t&uacute;torom odbornej praxe,</span></p>
    <p style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">3.3 zabezpeč&iacute; doručenie dokladu &bdquo;V&yacute;kaz o vykonanej odbornej praxi&ldquo; najnesk&ocirc;r v</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">term&iacute;noch predp&iacute;san&yacute;ch garantom pre dan&yacute; semester,</span></p>
    <p style="margin-top:0pt; margin-left:18pt; text-indent:-18pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">3.4 okamžite, bez zbytočn&eacute;ho odkladu informuje garanta odbornej praxe o</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">probl&eacute;moch, ktor&eacute; br&aacute;nia plneniu odbornej praxe.</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:center; line-height:115%; font-size:11pt;"><strong><span style="font-family:Calibri;">III. V&scaron;eobecn&eacute; a</span></strong><strong><span style="font-family:Calibri;">&nbsp;</span></strong><strong><span style="font-family:Calibri;">z&aacute;verečn&eacute; ustanovenia</span></strong></p>
    <ol style="margin:0pt; padding-left:0pt;">
        <li style="margin-top:0pt; margin-left:13.35pt; line-height:115%; padding-left:0.85pt; font-family:Calibri; font-size:11pt;">Dohoda sa uzatv&aacute;ra na dobu určit&uacute;. Dohoda nadob&uacute;da platnosť a&nbsp;&uacute;činnosť dňom podp&iacute;sania obidvomi zmluvn&yacute;mi stranami. Obsah dohody sa m&ocirc;že meniť p&iacute;somne len po s&uacute;hlase jej zmluvn&yacute;ch str&aacute;n.</li>
        <li style="margin-top:0pt; margin-left:13.35pt; line-height:115%; padding-left:0.85pt; font-family:Calibri; font-size:11pt;">Diela vytvoren&eacute; &scaron;tudentom sa spravuj&uacute; režimom zamestnaneck&eacute;ho diela podľa &sect; 90 z&aacute;kona č. 185/2015 Z. z. (Autorsk&yacute; z&aacute;kon). V&nbsp;pr&iacute;pade, že sa dielo stane &scaron;kolsk&yacute;m dielom podľa &sect; 93 citovan&eacute;ho z&aacute;kona, Fakulta pr&iacute;rodn&yacute;ch vied a informatiky Univerzity Kon&scaron;tant&iacute;na Filozofa v&nbsp;Nitre t&yacute;mto udeľuje Poskytovateľovi odbornej praxe v&yacute;hradn&uacute;, časovo a teritori&aacute;lne neobmedzen&uacute;, bezodplatn&uacute; licenciu na ak&eacute;koľvek použitie alebo sublicenciu diel vytvoren&yacute;ch &scaron;tudentom počas trvania odbornej praxe.</li>
        <li style="margin-top:0pt; margin-left:13.35pt; line-height:115%; padding-left:0.85pt; font-family:Calibri; font-size:11pt;">Dohoda sa uzatv&aacute;ra v&nbsp;3 vyhotoveniach, každ&aacute; zmluvn&aacute; strana obdrž&iacute; jedno vyhotovenie dohody.</li>
    </ol>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">V</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">Nitre, dňa....................</span><span style="width:32.66pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;</span><span style="width:27.94pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">&nbsp;&nbsp;</span><span style="font-family:Calibri;">V</span><span style="font-family:Calibri;">&nbsp;</span><span style="font-family:Calibri;">........., dňa ....................</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">...................................................................</span><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">.............................................................................</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">prof. RNDr. Franti&scaron;ek Petrovič, PhD., MBA</span><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="width:19.88pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">&nbsp;meno a priezvisko</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Calibri;">dekan FPVaI UKF v Nitre</span><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="width:10.22pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">&scaron;tatut&aacute;rny z&aacute;stupca pracoviska odb. praxe</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;</span></p>
    <p style="margin-top:0pt; text-align:right; line-height:115%; font-size:11pt;"><span style="width:263.25pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">&nbsp;.............................................................................</span></p>
    <p style="margin-top:0pt; line-height:115%; font-size:11pt;"><span style="font-family:Calibri;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="width:3.51pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="width:35.4pt; font-family:Calibri; display:inline-block;">&nbsp;</span><span style="font-family:Calibri;">&nbsp;meno a priezvisko &scaron;tudenta</span></p>
    <div style="clear:both;">
        <p class="Footer" style="text-align:center; font-size:9pt;">1</p>
        <p class="Footer">&nbsp;</p>
    </div>
</div>
<p style="bottom: 10px; right: 10px; position: absolute;"><a href="https://wordtohtml.net/?utm_source=wth_free_doc_conversion_link&utm_medium=external" target="_blank" style="font-size:11px; color: #d0d0d0;">Converted to HTML with WordToHTML.net</a><span style="font-size:11px; color: #d0d0d0;">&nbsp;|&nbsp;</span><a href="https://documentconverter.pro/?utm_source=wth_free_doc_conversion_link&utm_medium=external" target="_blank" style="font-size:11px; color: #d0d0d0;">Document Converter for Windows</a></p>
</body>

</html>
