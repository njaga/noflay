<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        .header {
            text-align: center;
            line-height: 1.2;
            padding: 10px 0;
        }
        .logo {
            max-width: 150px;
            max-height: 50px;
            display: block;
            margin: 0 auto 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo {{ $company->name }}" class="logo">
        @elseif($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo {{ $company->name }}" class="logo">
        @else
            <p>Logo non disponible</p>
        @endif
        <p class="company-name">{{ $company->name }}</p>
        <div class="company-details">
            <p>{{ $company->address }}</p>
            <p>Tél : {{ $company->phone }} | Email : {{ $company->email }}</p>
            @if($company->website)
                <p>Site web : {{ $company->website }}</p>
            @endif
        </div>
    </div>
</body>
</html>