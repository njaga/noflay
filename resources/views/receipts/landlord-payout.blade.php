<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de versement - {{ $transaction_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { text-align: center; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reçu de versement</h1>
        <p>N° {{ $transaction_id }}</p>
    </div>

    <div class="content">
        <p><strong>Date de versement :</strong> {{ $payout_date }}</p>
        <p><strong>Bailleur :</strong> {{ $landlord_name }}</p>
        <p><strong>Adresse :</strong> {{ $landlord_address }}</p>

        <table>
            <tr>
                <th>Description</th>
                <th>Montant</th>
            </tr>
            <tr>
                <td>Montant brut</td>
                <td>{{ $amount }} F CFA</td>
            </tr>
            <tr>
                <td>TVA</td>
                <td>{{ $tva_amount }} F CFA</td>
            </tr>
            <tr>
                <td>Commission</td>
                <td>{{ $commission_amount }} F CFA</td>
            </tr>
            <tr>
                <td><strong>Montant net versé</strong></td>
                <td><strong>{{ $net_amount }} F CFA</strong></td>
            </tr>
        </table>

        <p><strong>Méthode de paiement :</strong> {{ $payment_method }}</p>
        @if($payment_method === 'bank' || $payment_method === 'cheque_cash')
            <p><strong>Numéro de chèque :</strong> {{ $cheque_number }}</p>
        @endif
        @if($payment_method === 'cheque_cash')
            <p><strong>Montant du chèque :</strong> {{ $cheque_amount }} F CFA</p>
            <p><strong>Montant en espèces :</strong> {{ $cash_amount }} F CFA</p>
        @endif
    </div>

    <div class="footer">
        <p>Ce reçu est généré électroniquement et ne nécessite pas de signature.</p>
        <p>{{ $company_name }} - {{ $company_address }}</p>
    </div>
</body>
</html>
