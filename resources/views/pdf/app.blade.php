<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @font-face {
            font-family: 'Zawgyi-One';
            src: url("{{ storage_path('fonts/Zawgyi-One.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Poppins';
            src: url("{{ storage_path('fonts/poppins-regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Poppins';
            src: url("{{ storage_path('fonts/poppins-bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'Poppins', 'Zawgyi-One', sans-serif;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            font-family: 'Poppins', 'Zawgyi-One', sans-serif;
        }

        .table-border thead th,
        .table-border tbody td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-border th {
            background-color: #f4f4f4;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-sm-gray {
            display: block;
            font-size: 12px;
            color: gray;
        }

        .logo {
            width: 70px;
        }

        .block {
            display: block;
        }

        .font-w-medium {
            font-weight: bold;
        }

        .mb-3 {
            margin-bottom: 0.5rem;
        }

        .mb-5 {
            margin-bottom: 2rem;
        }

        .mt-5 {
            margin-top: 2rem;
        }

        .vertical-middle tr td {
            vertical-align: sub;
        }

        .font-w-light {
            font-weight: normal;
        }

        p {
            font-size: 14px;
            color: gray;
            font-family: 'Poppins', 'Zawgyi-One', sans-serif;
            line-height: 1em;
        }

        .float-right {
            float: right;
        }

        .main {
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <div class="main">
            <img src="{{ public_path('logo.jpg') }}" class="logo" alt="Khin Collection">
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>
