@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    $containerNav = $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp
<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20])</span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
        </a>
        @if (isset($menuHorizontal))
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="ti ti-x ti-md align-middle"></i>
            </a>
        @endif
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-md"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    @if (!isset($menuHorizontal))
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                    <i class="ti ti-search ti-md me-2 me-lg-4 ti-lg"></i>
                    <span class="d-none d-md-inline-block text-muted fw-normal">Search (Ctrl+/)</span>
                </a>
            </div>
        </div>
        <!-- /Search -->
    @endif

    <ul class="navbar-nav flex-row align-items-center ms-auto">
        @if (isset($menuHorizontal))
            <!-- Search -->
            <li class="nav-item navbar-search-wrapper">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill search-toggler"
                    href="javascript:void(0);">
                    <i class="ti ti-search ti-md"></i>
                </a>
            </li>
            <!-- /Search -->
        @endif

        <!-- Language -->
        <li class="nav-item dropdown-language dropdown">
            <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                href="javascript:void(0);" data-bs-toggle="dropdown">
                <i class='ti ti-language rounded-circle ti-md'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        href="{{ url('lang/en') }}" data-language="en" data-text-direction="ltr">
                        <span>English</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                        href="{{ url('lang/fr') }}" data-language="fr" data-text-direction="ltr">
                        <span>French</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                        href="{{ url('lang/ar') }}" data-language="ar" data-text-direction="rtl">
                        <span>Arabic</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'de' ? 'active' : '' }}"
                        href="{{ url('lang/de') }}" data-language="de" data-text-direction="ltr">
                        <span>German</span>
                    </a>
                </li>
            </ul>
        </li>
        <!--/ Language -->

        @if ($configData['hasCustomizer'] == true)
            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown">
                <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                    href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class='ti ti-md'></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class='ti ti-sun ti-md me-3'></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon-stars ti-md me-3"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i
                                    class="ti ti-device-desktop-analytics ti-md me-3"></i>System</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher -->
        @endif

       

        <!-- Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
            <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                aria-expanded="false">
                <span class="position-relative">
                    <i class="ti ti-bell ti-md"></i>
                    <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-0">
             
            </ul>
        </li>
        <!--/ Notification -->

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <img src="{{ Auth::user() ? (Auth::user()->hasRole('artist') ? asset(Auth::user()->artist->image) : Auth::user()->profile_photo_url) : asset('assets/img/avatars/1.png') }}"
                        alt="Profile Photo" class="rounded-circle"
                        onerror="this.onerror=null; this.src='{{ asset('assets/img/logo.png') }}'">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item mt-0"
                        href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-online">
                                    @auth
                                        @if (Auth::user()->hasRole('artist'))
                                            <img src="{{ asset(Auth::user()->artist->image) }}" alt
                                                class="rounded-circle" onerror="this.onerror=null; this.src='{{ asset('assets/img/logo.png') }}'">
                                        @else
                                            <img src="{{ Auth::user()->profile_photo_url }}" alt class="rounded-circle" onerror="this.onerror=null; this.src='{{ asset('assets/img/logo.png') }}'">
                                        @endif
                                    @else
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" onerror="this.onerror=null; this.src='{{ asset('assets/img/logo.png') }}'">
                                    @endauth
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">
                                    @if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @else
                                        John Doe
                                    @endif
                                </h6>
                                <small class="text-muted">Admin</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                </li>
                <li>
                    <a class="dropdown-item"
                        href="{{ Route::has('artist-profile') ? route('artist-profile') : url('pages/profile-user') }}">
                        <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider my-1 mx-n2"></div>
                </li>
                @if (Auth::check())
                    <li>
                        <div class="d-grid px-2 pt-2 pb-1">
                            <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <small class="align-middle">Logout</small>
                                <i class="ti ti-logout ms-2 ti-14px"></i>
                            </a>
                        </div>
                    </li>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                @else
                    <li>
                        <div class="d-grid px-2 pt-2 pb-1">
                            <a class="btn btn-sm btn-danger d-flex"
                                href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                                <small class="align-middle">Login</small>
                                <i class="ti ti-login ms-2 ti-14px"></i>
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>

<!-- Search Small Screens -->
<div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
    <input type="text"
        class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0"
        placeholder="Search..." aria-label="Search...">
    <i class="ti ti-x search-toggler cursor-pointer"></i>
</div>
<!--/ Search Small Screens -->
@if (isset($navbarDetached) && $navbarDetached == '')
    </div>
@endif
</nav>
<!-- / Navbar -->
