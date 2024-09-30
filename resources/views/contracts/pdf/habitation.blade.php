<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contrat de location à usage d'habitation</title>
    <style>
        @page {
            margin: 100px 50px 100px 50px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 1.2;
        }

        .footer {
            position: fixed;
            bottom: -80px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 1.2;
        }

        .logo {
            max-width: 150px;
            max-height: 50px;
            display: block;
            margin: 0 auto 10px;
        }

        .content {
            margin-top: 30px;
        }

        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 15px;
        }

        .signature {
            margin-top: 50px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="header">
        @if ($company->logo)
            <img src="{{ public_path('storage/' . $company->logo) }}" alt="Logo {{ $company->name }}" class="logo">
        @endif
        <p><strong>{{ $company->name }}</strong></p>
        <p>{{ $company->address }} | {{ $company->phone }} | {{ $company->email }}</p>
        <p>{{ $company->website ?? '' }}</p>
    </div>

    <div class="footer">
        <p>NINEA: {{ $company->NINEA }} | RCCM: {{ $company->RCCM }}</p>
        <p>{{ $company->name }} - {{ $company->address }}</p>
        <p>{{ $company->phone }} | {{ $company->email }} | {{ $company->website ?? '' }}</p>
    </div>

    <div class="content">
        <h1>Contrat de location à usage d'habitation</h1>

        <div class="section">
            <h2>Entre les soussignés :</h2>
            <p><strong>Le bailleur :</strong> {{ $landlord->name }}</p>
            <p><strong>Le locataire :</strong> {{ $tenant->first_name }} {{ $tenant->last_name }}</p>
        </div>

        @php
            use Carbon\Carbon;

            $startDate =
                $contract->start_date instanceof Carbon ? $contract->start_date : Carbon::parse($contract->start_date);
        @endphp

        <div class="section">
            <h2>Il a été convenu ce qui suit :</h2>
            <p>Le bailleur loue au locataire le logement situé à : {{ $property->address }}</p>
            <p>Pour une durée de {{ $contract->duration }} mois, à compter du {{ $startDate->format('d/m/Y') }}</p>
            <p>Moyennant un loyer mensuel de {{ number_format($contract->rent_amount, 0, ',', ' ') }} F CFA</p>
        </div>

        <div class="section">
            <h2>1. Durée du contrat</h2>
            <p>La présente location est consentie pour une durée de {{ $contract->duration }} mois, à compter du
                {{ $startDate->format('d/m/Y') }}. À l'expiration de cette période, le contrat pourra être reconduit
                tacitement pour une durée identique, sauf préavis de résiliation donné par l'une des parties au moins
                trois (3) mois avant l'échéance.</p>
        </div>

        <div class="section">
            <h2>2. Destination des lieux</h2>
            <p>Le logement est loué pour un usage d'habitation exclusivement. Le locataire s'engage à n'y exercer aucune
                activité commerciale, artisanale, ou professionnelle, sauf accord préalable et écrit du bailleur.</p>
        </div>

        <div class="section">
            <h2>3. Loyer et charges</h2>
            <p>Le loyer mensuel est fixé à {{ number_format($contract->rent_amount, 0, ',', ' ') }} F CFA, payable
                d'avance au plus tard le {{ $contract->payment_day ?? '5' }} de chaque mois. Les charges locatives
                récupérables, telles que définies par la législation en vigueur, seront également à la charge du
                locataire.</p>
        </div>

        <div class="section">
            <h2>4. Dépôt de garantie</h2>
            <p>Le locataire verse, à la signature du présent contrat, un dépôt de garantie équivalent à
                {{ $contract->deposit_amount ?? 2 }} mois de loyer, soit
                {{ number_format(($contract->deposit_amount ?? 2) * $contract->rent_amount, 0, ',', ' ') }} F CFA. Ce
                dépôt sera restitué au locataire, déduction faite des sommes éventuellement dues au bailleur, à la fin
                de la location après l'état des lieux de sortie.</p>
        </div>

        <div class="section">
            <h2>5. Entretien et réparations</h2>
            <p>Le locataire s'engage à entretenir les lieux loués en bon état et à effectuer les réparations locatives à
                sa charge. Le bailleur reste responsable des réparations importantes, sauf si elles sont causées par la
                négligence ou la faute du locataire.</p>
        </div>

        <div class="section">
            <h2>6. Assurance</h2>
            <p>Le locataire s'engage à souscrire une assurance couvrant les risques locatifs (incendie, dégâts des eaux,
                etc.) et à fournir une attestation d'assurance au bailleur chaque année.</p>
        </div>

        <div class="section">
            <h2>7. État des lieux</h2>
            <p>Un état des lieux sera dressé contradictoirement entre les parties lors de la remise des clés et à la
                restitution du logement. Cet état des lieux servira de référence pour la restitution du dépôt de
                garantie.</p>
        </div>

        <div class="section">
            <h2>8. Résiliation anticipée</h2>
            <p>Le locataire peut résilier le contrat à tout moment, sous réserve de respecter un préavis de trois (3)
                mois. Le bailleur ne peut résilier le contrat qu'en cas de manquement grave aux obligations du
                locataire, avec un préavis de trois (3) mois et après mise en demeure restée sans effet.</p>
        </div>

        <div class="section">
            <h2>9. Sous-location et cession du bail</h2>
            <p>La sous-location est interdite, sauf accord écrit du bailleur. Le locataire ne peut céder le présent bail
                sans l'accord écrit du bailleur.</p>
        </div>

        <div class="section">
            <h2>10. Litiges</h2>
            <p>En cas de litige relatif à l'interprétation ou à l'exécution du présent contrat, les parties s'engagent à
                rechercher une solution amiable avant de porter l'affaire devant les tribunaux compétents du Sénégal.
            </p>
        </div>

        <div class="signature">
            <p>Fait à ________________, le {{ now()->format('d/m/Y') }}</p>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <p>Signature du bailleur :</p>
                        <br><br>
                        <p>_______________________</p>
                        <p>{{ $landlord->name }}</p>
                    </td>
                    <td style="width: 50%;">
                        <p>Signature du locataire :</p>
                        <br><br>
                        <p>_______________________</p>
                        <p>{{ $tenant->first_name }} {{ $tenant->last_name }}</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
