<!-- resources/views/contracts/pdf/footer.blade.php -->
<html>
<head>
    <style>
        .footer {
            font-size: 10px;
            text-align: center;
            color: #777;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="footer">
        <p>NINEA: {{ $company->NINEA }} | RCCM: {{ $company->RCCM }}</p>
        <p>{{ $company->name }} - {{ $company->address }}</p>
        <p>{{ $company->phone }} | {{ $company->email }} | {{ $company->website ?? '' }}</p>
    </div>
</body>
</html>