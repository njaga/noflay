<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contrat de location à usage commercial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 15px;
        }

        .signature {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Contrat de location à usage commercial</h1>
    </div>

    <div class="section">
        <h2>Entre les soussignés :</h2>
        <p>Le bailleur : {{ $landlord->name }}</p>
        <p>Le locataire : {{ $tenant->first_name }} {{ $tenant->last_name }}</p>
    </div>

    <div class="section">
        <h2>Il a été convenu ce qui suit :</h2>
        <p>Le bailleur loue au locataire le local commercial situé à : {{ $property->address }}</p>
        <p>Pour une durée de {{ $contract->duration }} mois, à compter du {{ $contract->start_date }}</p>
        <p>Moyennant un loyer mensuel de {{ $contract->rent_amount }} FCFA hors taxes et hors charges</p>
    </div>

    <div class="section">
        <h2>1. Durée du contrat</h2>
        <p>La présente location est consentie et acceptée pour une durée de {{ $contract->duration }} mois à compter du {{ $contract->start_date }}. À l'issue de cette période, le contrat pourra être renouvelé par tacite reconduction, sauf dénonciation par l'une des parties par lettre recommandée avec accusé de réception au moins trois (3) mois avant l'échéance.</p>
    </div>

    <div class="section">
        <h2>2. Destination des lieux</h2>
        <p>Le local loué est destiné exclusivement à un usage commercial. Le locataire s'engage à respecter cette destination et à n'exercer aucune activité illicite ou contraire à l'ordre public dans les lieux.</p>
    </div>

    <div class="section">
        <h2>3. Conditions financières</h2>
        <p>Le loyer est fixé à {{ $contract->rent_amount }} FCFA hors taxes et hors charges, payable mensuellement d'avance entre les mains du bailleur ou sur le compte bancaire indiqué par ce dernier. Le locataire supportera également les charges locatives, y compris les impôts locaux et les taxes liées à l'exploitation du commerce.</p>
    </div>

    <div class="section">
        <h2>4. Dépôt de garantie</h2>
        <p>Le locataire verse au bailleur un dépôt de garantie équivalent à {{ $contract->deposit_amount }} mois de loyer, soit {{ $contract->deposit_total }} FCFA, qui sera restitué à la fin du contrat, déduction faite des sommes dues par le locataire au titre des réparations ou des loyers impayés.</p>
    </div>

    <div class="section">
        <h2>5. Entretien des locaux</h2>
        <p>Le locataire est tenu de maintenir les locaux en bon état et d'effectuer les réparations locatives à sa charge conformément aux dispositions légales. Toute modification ou aménagement des locaux doit être préalablement approuvé par écrit par le bailleur.</p>
    </div>

    <div class="section">
        <h2>6. Assurance</h2>
        <p>Le locataire s'engage à souscrire une assurance couvrant les risques locatifs et à en justifier auprès du bailleur à la signature du présent contrat puis chaque année. En cas de sinistre, le locataire doit en informer immédiatement le bailleur.</p>
    </div>

    <div class="section">
        <h2>7. Résiliation anticipée</h2>
        <p>En cas de non-respect des obligations contractuelles par l'une des parties, le présent contrat pourra être résilié de plein droit, après mise en demeure restée infructueuse pendant un délai de trente (30) jours. En outre, le locataire pourra résilier le contrat à tout moment avec un préavis de trois (3) mois, sous réserve du paiement des loyers dus jusqu'à la fin du préavis.</p>
    </div>

    <div class="section">
        <h2>8. État des lieux</h2>
        <p>Un état des lieux d'entrée sera établi contradictoirement entre les parties au moment de la remise des clés. De même, un état des lieux de sortie sera établi à la restitution des locaux. Les frais éventuels de remise en état seront à la charge du locataire.</p>
    </div>

    <div class="section">
        <h2>9. Clauses particulières</h2>
        <p>Les parties peuvent convenir de clauses spécifiques adaptées à leur situation, telles que la possibilité de sous-location, l'aménagement des locaux, ou la révision du loyer en cours de bail.</p>
    </div>

    <div class="section">
        <h2>10. Litiges</h2>
        <p>Tout différend relatif à l'exécution ou à l'interprétation du présent contrat sera soumis à la juridiction compétente du lieu de situation de l'immeuble, conformément aux dispositions du Code des obligations civiles et commerciales du Sénégal.</p>
    </div>

    <div class="signature">
        <p>Fait à ________________, le ________________</p>
        <p>Signature du bailleur : ___________________ Signature du locataire : ___________________</p>
    </div>
</body>

</html>
