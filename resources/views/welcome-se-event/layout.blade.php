<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Twilio') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@400;900&family=Playfair+Display&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script
      src="https://code.jquery.com/jquery-2.2.4.min.js"
      integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
      crossorigin="anonymous"></script>
    <script src="https://media.twiliocdn.com/sdk/js/sync/releases/2.0.1/twilio-sync.min.js"></script>

    @if (isset($games))
        <script>
            window.games = {!! $games->pluck('id') !!};
        </script>
    @endif


    @if (isset($game))
        <script>
            window.game = {!! $game->id !!};
        </script>
    @endif

    @if (isset($questions))
        <script>
            window.questions = {!! $questions->pluck('id') !!};
        </script>
    @endif

    @if (isset($question))
        <script>
            window.question = {!! $question->id !!};
        </script>
    @endif

    @if (isset($question))
        <script>
            window.question_type = "{!! $question->type !!}";
        </script>
    @endif

    <!-- Styles -->
    <link href="{{ asset('welcome-se-event/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="red-stripe"></div>
    <div id="twilio-logo">
        <svg fill="#F22F46" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M15 0C6.7 0 0 6.7 0 15s6.7 15 15 15 15-6.7 15-15S23.3 0 15 0zm0 26C8.9 26 4 21.1 4 15S8.9 4 15 4s11 4.9 11 11-4.9 11-11 11zm6.8-14.7c0 1.7-1.4 3.1-3.1 3.1s-3.1-1.4-3.1-3.1 1.4-3.1 3.1-3.1 3.1 1.4 3.1 3.1zm0 7.4c0 1.7-1.4 3.1-3.1 3.1s-3.1-1.4-3.1-3.1c0-1.7 1.4-3.1 3.1-3.1s3.1 1.4 3.1 3.1zm-7.4 0c0 1.7-1.4 3.1-3.1 3.1s-3.1-1.4-3.1-3.1c0-1.7 1.4-3.1 3.1-3.1s3.1 1.4 3.1 3.1zm0-7.4c0 1.7-1.4 3.1-3.1 3.1S8.2 13 8.2 11.3s1.4-3.1 3.1-3.1 3.1 1.4 3.1 3.1z"/></svg>
    </div>
    <div id="app" class="magic-container">

        @yield('content')

    </div>

    <script src="{{ asset('welcome-se-event/js/app.js') }}" defer></script>
</body>
</html>
