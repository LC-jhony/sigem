<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="{{ public_path('print_pdf.css') }}" type="text/css">
<style>
       :root {
       --primary: #2c3e50;
       --secondary: #3498db;
       --success: #27ae60;
       --warning: #f39c12;
       --danger: #e74c3c;
       --light: #f8f9fa;
       --gray: #95a5a6;
       --border: #e0e6ed;
   }
      .page {
       page-break-after: always;
       max-width: 1000px;
       margin: 0 auto;
       position: relative;
       padding-top: 10px;
   }
    .header {
       background: #f9f9f9;
       color: white;
       padding: 20px;
   }

   .header-top {
       display: table;
       width: 100%;
   }
   
   .logo {
       display: table-cell;
       vertical-align: middle;
   }
   .logo-text {
       font-size: 20px;
       font-weight: bold;
       margin-left: 10px;
       display: inline-block;
       vertical-align: middle;
   }

   .report-title {
       display: table-cell;
       text-align: right;
       vertical-align: middle;
   }

   .report-title h1 {
       font-size: 20px;
       font-weight: bold;
       margin-bottom: 5px;
       color: #0f172a;
   }

   .report-number {
       background: #243c94;
       color: #fff;
       padding: 6px 10px;
       border-radius: 8px;
       font-size: 12px;
       display: inline-block;
   }

   .vehicle-info {

       /* margin-top   : 10px; */
       /* border       : 1px solid #ccc;
      border-radius: 8px; */
       padding: 10px;
       font-size: 12px;
   }

   .info-item {
       text-align: center;
       padding: 6px;
       color: #666;
       width: 22%;
       display: inline-block;
       vertical-align: top;
       margin-top: 10px;
       border: 1px solid #ccc;
       border-radius: 8px;
   }

   .info-label {
       font-weight: bold;
       color: #666;
       font-size: 11px;
   }

   .highlight {
       color: #1e40af;
       font-weight: bold;
   }

   .section {
       margin-bottom: 15px;
   }

   .section-title {
       font-size: 12px;
       font-weight: 700;
       padding: 6px 10px;
       background-color: #f8f9fa;
       border-radius: 4px;
       margin-bottom: 10px;
       display: flex;
       align-items: center;
       gap: 6px;
       color: var(--primary);
       border-left: 3px solid var(--secondary);
   }
     .table {
   width: 100%;
            font-size: xx-small;
            border-collapse: collapse;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;

        }

        thead tr th {
            background-color: var(--primary);
            border: 1px solid #ddd;
            color: var(--light)
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
</style>
</head>

<body>
    <div class="header">
        <div class="header-top">
            <div class="logo">
                <div class="logo-icon">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo.png'))) }}"
                        alt="Logo" style="width: 140px;">
                </div>
            </div>
         {{ $head }}
        </div>

    </div> <br>
    {{ $slot }}
    </div>
</body>

</html>
