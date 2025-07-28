<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pakbon {{ $order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
            height: 100vh;
            box-sizing: border-box;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            max-height: 100vh;
        }

        .main-content {
            display: flex;
            flex: 1;
            gap: 20px;
            min-height: 0; /* Important for flex child to shrink */
        }

        .pakbon-section {
            flex: 1.5;
            display: flex;
            flex-direction: column;
        }

        .label-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header .date {
            font-size: 14px;
            margin-top: 5px;
        }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .info-left, .info-right {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .info-right {
            padding-left: 4%;
        }

        .info-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .address {
            line-height: 1.5;
        }

        .items-section {
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }

        .items-table td {
            font-size: 11px;
        }

        .items-table .center {
            text-align: center;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-style: italic;
            font-size: 11px;
            flex-shrink: 0;
        }

        .company-info {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 10px;
            text-align: right;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }

        .label-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .label-container h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-align: center;
            flex-shrink: 0;
        }

        .label-image-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #aaa;
            background-color: #fafafa;
            min-height: 150px;
            overflow: hidden;
        }

        .label-image-wrapper img {
            object-fit: cover;
            max-width: 100%;
            max-height: 100%;
        }

        .no-label {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-style: italic;
        }

        @media print {
            body {
                margin: 0;
                height: 100vh;
            }

            .page-container {
                height: 100vh;
            }

            @page {
                margin: 1cm;
                size: A4;
            }
        }
    </style>
</head>
<body>
    <div class="company-info">
        QLS assignment
    </div>

    <div class="page-container">
        <div class="header">
            <h1>Pakbon {{ $order_number }}</h1>
            <div class="date">Datum: {{ $date }}</div>
        </div>

        <hr>

        <div class="main-content">
            <div class="pakbon-section">
                <div class="info-section">
                    <div class="info-left">
                        <div class="info-title">Verzendadres:</div>
                        <div class="address">
                            {{ $customer['name'] }}<br>
                            {{ $customer['street'] }} {{ $customer['house_number'] }}<br>
                            {{ $customer['postal_code'] }} {{ $customer['city'] }}<br>
                            NL
                        </div>
                    </div>

                    <div class="info-right">
                        <div class="info-title">Factuuradres:</div>
                        <div class="address">
                            {{ $customer['name'] }}<br>
                            {{ $customer['street'] }} {{ $customer['house_number'] }}<br>
                            {{ $customer['postal_code'] }} {{ $customer['city'] }}<br>
                            E: {{ $customer['email'] }}<br>
                            T: {{ $customer['phone'] }}
                        </div>
                    </div>
                </div>

                <div class="items-section">
                    <table class="items-table">
                        <thead>
                        <tr>
                            <th>Aantal</th>
                            <th>Artikel</th>
                            <th>SKU</th>
                            <th>EAN</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td class="center">{{ $item['amount_ordered'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['sku'] }}</td>
                                <td>{{ $item['ean'] ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="label-section">
                <div class="label-container">
                    <h3>Verzendlabel</h3>
                    <div class="label-image-wrapper">
                        @if(!empty($label_image))
                            <img src="data:image/png;base64,{{ $label_image }}" alt="Verzendlabel">
                        @else
                            <div class="no-label">Geen label beschikbaar</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
