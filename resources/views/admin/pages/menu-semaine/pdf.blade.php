<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>{{ $menuSemaine->titre_affiche }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 16mm 14mm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
        }

        /* ── En-tête ── */
        .pdf-header {
            width: 100%;
            border-bottom: 3px solid #eb0029;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .pdf-brand {
            font-size: 22px;
            font-weight: bold;
            color: #eb0029;
            letter-spacing: 1px;
        }
        .pdf-brand span { color: #f85d05; }
        .pdf-tagline { font-size: 10px; color: #888; margin-top: 2px; }

        .pdf-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 14px 0 2px;
        }
        .pdf-periode { font-size: 11px; color: #666; margin-bottom: 12px; }

        /* ── Bandeau tarif réduit ── */
        .pdf-reduction-box {
            width: 100%;
            background-color: #fdf2ea;
            border: 1px solid #f8c99a;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .pdf-reduction-label {
            font-size: 11px;
            font-weight: bold;
            color: #eb0029;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .pdf-reduction-msg { font-size: 11px; color: #444; margin-top: 3px; }

        /* ── Jour ── */
        .pdf-day-title {
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background-color: #1a0000;
            padding: 6px 10px;
            border-radius: 4px 4px 0 0;
            margin-top: 16px;
        }
        table.pdf-plats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.pdf-plats th {
            background-color: #f5f5f5;
            font-size: 9px;
            text-transform: uppercase;
            color: #777;
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
        }
        table.pdf-plats td {
            font-size: 11px;
            padding: 7px 8px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }
        .pdf-plat-nom { font-weight: bold; color: #1a1a1a; }
        .pdf-plat-desc { font-size: 9.5px; color: #999; }
        .pdf-prix-normal { font-weight: bold; color: #1a1a1a; white-space: nowrap; }
        .pdf-prix-reduit { font-weight: bold; color: #11998e; white-space: nowrap; }
        .pdf-empty-day { font-size: 10px; color: #aaa; font-style: italic; padding: 8px; }

        /* ── Pied de page / contact ── */
        .pdf-footer {
            margin-top: 24px;
            border-top: 2px solid #eb0029;
            padding-top: 12px;
        }
        table.pdf-footer-table { width: 100%; border-collapse: collapse; }
        table.pdf-footer-table td { vertical-align: top; padding: 0; }

        .pdf-wa-box {
            background-color: #eb0029;
            border-radius: 6px;
            padding: 10px 14px;
            width: 100%;
        }
        .pdf-wa-title { font-size: 12px; font-weight: bold; color: #fff; }
        .pdf-wa-value { font-size: 13px; font-weight: bold; color: #fff; margin-top: 2px; }

        .pdf-infos { font-size: 10px; color: #555; padding-left: 14px; }
        .pdf-infos div { margin-bottom: 4px; }
        .pdf-infos strong { color: #1a1a1a; }
    </style>
</head>

<body>

    <div class="pdf-header">
        <div class="pdf-brand">AKA<span>DI</span></div>
        <div class="pdf-tagline">Cuisine ivoirienne &mdash; commandez en ligne ou sur place</div>
    </div>

    <div class="pdf-title">{{ $menuSemaine->titre_affiche }}</div>
    <div class="pdf-periode">
        Du {{ \Carbon\Carbon::parse($menuSemaine->date_debut)->locale('fr')->isoFormat('dddd D MMMM') }}
        au {{ \Carbon\Carbon::parse($menuSemaine->date_fin)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
    </div>

    <div class="pdf-reduction-box">
        <div class="pdf-reduction-label">Tarif réduit automatique</div>
        <div class="pdf-reduction-msg">
            À partir de <strong>{{ $menuSemaine->seuil_jours }} jour(s)</strong> commandés sur la semaine, le prix réduit
            s'applique automatiquement à chaque plat sélectionné &mdash; sans code, sans démarche.
        </div>
    </div>

    @forelse ($menuSemaine->menusJour as $menuJour)
        <div class="pdf-day-title">
            {{ \Carbon\Carbon::parse($menuJour->date)->locale('fr')->isoFormat('dddd D MMMM') }}
        </div>

        @if ($menuJour->menuProduits->count() > 0)
            <table class="pdf-plats">
                <thead>
                    <tr>
                        <th style="width:38%">Plat</th>
                        <th style="width:32%">Description</th>
                        <th style="width:15%">Prix normal</th>
                        <th style="width:15%">Prix réduit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menuJour->menuProduits as $plat)
                        <tr>
                            <td class="pdf-plat-nom">{{ $plat->nom }}</td>
                            <td class="pdf-plat-desc">{{ $plat->description }}</td>
                            <td class="pdf-prix-normal">{{ number_format($plat->prix_normal, 0, ',', ' ') }} FCFA</td>
                            <td class="pdf-prix-reduit">{{ number_format($plat->prix_reduit, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="pdf-empty-day">Aucun plat prévu ce jour.</div>
        @endif
    @empty
        <p style="font-size:11px;color:#999;">Aucun jour disponible pour cette semaine.</p>
    @endforelse

    <div class="pdf-footer">
        <table class="pdf-footer-table">
            <tr>
                <td style="width:45%">
                    <div class="pdf-wa-box">
                        <div class="pdf-wa-title">Réservez via WhatsApp</div>
                        <div class="pdf-wa-value">+225 07 58 83 83 38</div>
                    </div>
                </td>
                <td style="width:55%">
                    <div class="pdf-infos">
                        <div><strong>Adresse :</strong> Angré, derrière la pharmacie Arcade</div>
                        <div><strong>Horaires :</strong> 10h30 &ndash; 18h00</div>
                        <div><strong>Commande en ligne :</strong> {{ route('carte-menu', $menuSemaine->lien_token) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
