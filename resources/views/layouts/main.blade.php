<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weather Information</title>

        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <form method="POST" action="/weather">
                                @csrf
                                <div class="row">
                                    <div class="col-5"><h2><a href="/">Weather Information</a></h2></div>
                                    <div class="col-3 mr-2"><input type="text" name="city" placeholder="Enter city..."/></div>
                                    <div class="col-3 ml-2"><button class="btn-sm" type="submit">Show weather</button></div>
                                </div>
                            </form>
                        </div>


                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
