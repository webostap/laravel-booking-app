<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">  
    <style type="text/css">
        body {
            background: white
        }
        .collection {
            width: max-content;
        }
        input[type="date"] {
            width: 110px !important
        }
        select.browser-default {
            width: auto !important
        }
        .flex-center {
            display: flex;
            align-items: center;
            margin-left: -15px;
            margin-right: -15px;
        }
        .inline-form {
            display: inline-flex;
            align-items: center;
        }
        .inline-form > * {
            margin: 0 15px !important;
        }
    </style>
</head>
<body>

  <nav class="cyan teal">
    <div class="nav-wrapper container">
      <a href="/" class="brand-logo hide-on-med-and-down">Home</a>
      <ul id="nav-mobile" class="right ">
        <li><a href="/admin">Заказы</a></li>
        <li><a href="/admin/edit">Настройка</a></li>
        <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Выйти
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
      </ul>
    </div>
  </nav>
        

<div class="container" style="margin-top: 50px;">

    @include('errors')
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
