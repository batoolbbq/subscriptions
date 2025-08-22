@if (!isset($noFooter))
    <footer>
        <div class="footer">
            <h2 class="ImpoText text-center">{{ __('Visit our page at') }}
            </h2>
            <div class="icons">
                <a href><img src="{{ asset('assets/home/imgs/facebook.png') }} " alt></a>
                <a href><img src="{{ asset('assets/home/imgs/instagram.png') }}" alt></a>
            </div>
        </div>
    </footer>
@endif
