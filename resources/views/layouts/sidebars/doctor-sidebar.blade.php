<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar start-->
        <div class="side-menu-fixed">
            <div class="scrollbar side-menu-bg">
                <ul class="nav navbar-nav side-menu" id="sidebarnav">
                    <li>
                        <a href="{{ route('doctor.dashboard') }}"><i class="ti-home"></i><span
                                class="right-nav-text">{{ __('Dashboard') }}</span></a>
                    </li>


                    <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title"> {{ __('Components') }} </li>
                    <li>
                        <a href="{{ route('doctor.card.index') }}"><i class="ti-shopping-cart"></i><span
                                class="right-nav-text">{{ __('Reservations') }}</span> </a>
                    </li>
                </ul>
                </li>
                </ul>
            </div>
        </div>

        <!-- Left Sidebar End-->

        <!--=================================
