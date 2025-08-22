<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}">
    <title>@yield('title')</title>


</head>

<body>
    <script>
        const storageKey = 'theme-preference';

        const onClick = () => {
            theme.value = theme.value === 'light' ? 'dark' : 'light';


            document.body.setAttribute("data-bs-theme", theme.value);

            document.body.classList.toggle('dark', theme.value === 'dark');


            setPreference();
        };

        const getColorPreference = () => {

            if (localStorage.getItem(storageKey)) {
                return localStorage.getItem(storageKey);
            } else {

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
        };

        const setPreference = () => {
            localStorage.setItem(storageKey, theme.value);
            reflectPreference();
        };

        const reflectPreference = () => {
            console.log(theme.value);

            document.querySelector('#theme-toggle')?.setAttribute('aria-label', theme.value);
            document.firstElementChild.setAttribute('data-bs-theme', theme.value);

            document.body.classList.toggle('dark', theme.value === 'dark');
        };

        const theme = {
            value: getColorPreference(),
        };


        reflectPreference();

        window.onload = () => {

            reflectPreference();


            document.querySelector('#theme-toggle').addEventListener('click', onClick);
        };


        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', ({
            matches: isDark
        }) => {
            theme.value = isDark ? 'dark' : 'light';
            setPreference();
        });
    </script>
    <div id="loader">
        <div class="loader-content d-flex flex-column align-items-center justify-content-center">
            <img src="{{ asset('assets/home/imgs/logo.png') }}" alt="Loading Image" class="loader-image ">
            <progress value="0" max="100" id="progress-bar"></progress>
        </div>
    </div>


    <div id="app">
        @include('layouts.home.navbar')

        <main class="py-4">
            @yield('content')
        </main>
        @include('sweetalert::alert')
        @include('layouts.home.footer')

    </div>

    <script src="{{ asset('assets/home/js/Jquery.min.js') }}"></script>
    <script src="{{ asset('assets/home/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/home/js/all.min.js') }}"></script>
    <script src="{{ asset('assets/home/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/home/js/javascript.js') }}"></script>

    @yield('js')


    <script>
        window.addEventListener('load', function() {
            var progressBar = document.getElementById('progress-bar');
            var loader = document.getElementById('loader');
            var content = document.getElementById('content');
            var progress = 0;
            var interval = setInterval(function() {
                progress += 1;
                progressBar.value = progress;

                if (progress >= 100) {
                    clearInterval(interval);
                    $('#loader').fadeOut();
                }
            }, 1);

        });
    </script>



</body>

</html>
