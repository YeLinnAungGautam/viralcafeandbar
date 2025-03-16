<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order confirmation from Khin Collection</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        .logo {
            width: 70px;
        }

        .font-bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-border thead th,
        .table-border tbody td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-border th {
            background-color: #f4f4f4;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .banner-logo {
            padding: 15px;
            text-align: center
        }

        .list-style-none {
            list-style: none;
            padding: 0px;
            margin: 0px;
        }

        .line-h-1 {
            line-height: 2em;
        }

        .mb-5 {
            margin-bottom: 2rem;
        }

        .m-0 {
            margin-bottom: 0px
        }

        hr {
            border: 0.01rem solid #ccc;
        }

        text-red {
            color: red !important;
        }

        #footer,
        #t-and-c {
            margin-bottom: 10px !important
        }

        #footer>p,
        #t-and-c>p {
            margin: 0
        }

        #t-and-c>h3 {
            margin: 0
        }
    </style>
</head>

<body>
    <section class="container">
        <header class="banner-logo">
            <img class="logo" src="https://kc.myanmarcafe.trade/logo.png" alt="KC">
        </header>
        @yield('content')
    </section>
</body>

</html>
