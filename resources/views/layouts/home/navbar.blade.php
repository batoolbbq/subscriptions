<header>
    <div class="imgWithText">
        <img src="{{ asset('assets/logo.jpg') }}" class="logo" alt="{{ __('جاري التحميل') }}">
        <a href="#" class="textlogo textName"> {{ __('جاري التحميل') }} </a>
    </div>

    <nav>
        <div class="Menu" id="textlogo"><img src="imgs/menu.png" alt height="30rem" width="30rem"></div>
        <ul id="ul">
            <li><a href="{{ route('home') }}#top" class="navBTN">{{ __('Home') }}</a></li>
            <li><a href="{{ route('services.index') }}" class="navBTN">{{ __('Services') }}</a></li>
            {{-- <li><a href="about.php" class="navBTN">{{ __('حول') }}</a></li> --}}
            <li><a href="{{ route('contact.show', 'index') }}" class="navBTN">{{ __('Customer Reviews') }}</a></li>
            <li><a href="{{ route('contact.index') }}" class="navBTN">{{ __('Contact Us') }}</a></li>
        </ul>
    </nav>

    <a type="button" class="theme-toggle" id="theme-toggle" title="Toggles light & dark" aria-label="auto"
        aria-live="polite">
        <svg class="sun-and-moon" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24">
            <mask class="moon" id="moon-mask">
                <rect x="0" y="0" width="100%" height="100%" fill="white" />
                <circle cx="24" cy="10" r="6" fill="black" />
            </mask>
            <circle class="sun" cx="12" cy="12" r="6" mask="url(#moon-mask)" fill="currentColor" />
            <g class="sun-beams" stroke="currentColor">
                <line x1="12" y1="1" x2="12" y2="3" />
                <line x1="12" y1="21" x2="12" y2="23" />
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                <line x1="1" y1="12" x2="3" y2="12" />
                <line x1="21" y1="12" x2="23" y2="12" />
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
            </g>
        </svg>
    </a>



    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class=" fa fa-language" aria-hidden="true"></i>
        </a>

        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a rel="alternate"class="dropdown-item" hreflang="{{ $localeCode }}"
                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $properties['native'] }}
                </a>
            @endforeach


        </ul>
    </div>





    @auth('web')
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa fa-user" aria-hidden="true"></i>
            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li class="m-0"><a class="dropdown-item" href="{{ route('card.index') }}"><i class="fa fa-user"
                            aria-hidden="true"></i> {{ __('My Bookings') }}</a></li>
                <li class="m-0">
                    <a href="{{ route('logout') }}" class="dropdown-item"><i class="fa fa-sign-out-alt"
                            aria-hidden="true"></i> {{ __('Logout') }}</a>
                </li>
            </ul>
        </div>
    @endauth
    @guest
        <a href="{{ Auth::guard('admin')->check() || Auth::guard('doctor')->check() ? route('login') : route('dashboard') }}"
            class="btn btn-outline-success">{{ Auth::guard('admin')->check() || Auth::guard('doctor')->check() ? __('Dashboard') : __('Login') }}</a>
    @endguest
</header>
