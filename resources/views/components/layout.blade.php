<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

    <style>
        body {
            padding-top: 60px;
            /* Espacio suficiente para el header fijo en TODAS las páginas */
            padding-bottom: 45px;
            /* Para no chocar con el footer */
        }

        h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        /** Margins for the PDF pages */
        @page {
            margin: 100px 50px 70px 50px;
        }

        header {
            position: fixed;
            top: -50px;
            left: 0px;
            right: 0px;
            height: 85px;
            padding: 5px 20px;
            overflow: hidden;
            /* ← Clave para evitar que se desborde */
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            padding: 10px 20px;
            font-size: 9pt;
            text-align: center;
        }

        .header-container {
            display: table;
            width: 100%;
        }

        .header-cell {
            display: table-cell;
            vertical-align: top;
        }

        .logo {


            text-align: center;
            line-height: 80px;
            font-size: 10pt;
        }

        .center-space {
            width: 100%;
        }

        .company-info {
            text-align: right;
            font-size: 8pt;
            line-height: 1.2em;
            padding-right: 5px;
            width: 230px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .company-info .name {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
        }

        .company-info .info-group>div {
            margin: 1px 0;
        }

        main {
            margin-top: 30px;
            font-size: 10pt;
        }

        .footer span {
            font-weight: bold;
        }

        table {

            font-size: xx-small;
            border-collapse: collapse;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;

        }

        thead tr th {
            border: 1px solid #ddd;
        }


        tbody tr td {
            font-size: x-small;
            padding: 2px 5px;
            border: 1px solid #ddd;
        }

        tfoot tr td {
            font-weight: 100;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray;
        }

    </style>
</head>

<body>

    <header>
        <div class="header-container">
            <div class="header-cell" style="width: 90px;">
                <div class="logo">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}" alt="Logo" style="width: 90px; height: auto;">
                </div>
            </div>
            <div class="header-cell center-space"></div>
            <div class="header-cell company-info">
                <div class="name">Shinra Electric Power Company</div>
                <div class="info-group">
                    <div>Rep: John Doe</div>
                    <div>Midgar Sector 5, Reactor St.</div>
                    <div>RFC: SHN-00112233</div>
                    <div>Tel: (555) 123-4567</div>
                    <div>Fax: (555) 987-6543</div>
                </div>
            </div>
        </div>
    </header>


    <footer>

    </footer>

    <main>
        {{ $slot }}
    </main>

</body>
</html>
