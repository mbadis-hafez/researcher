@extends('layouts/layoutMaster')

@section('title', 'Artist - Artworks')

<!-- Page -->
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-profile.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection


@section('page-script')
    @vite(['resources/assets/js/artist-artwork-list.js'])
@endsection



@section('content')
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top">
                </div>
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset($artist->image) }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img">
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-6">{{ @ucfirst($artist->name) }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-palette ti-lg'></i>
                                        <span class="fw-medium">
                                            {{ @ucfirst($artist->user->getRoleNames()->first()) }}
                                        </span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-map-pin ti-lg'></i><span
                                            class="fw-medium">{{ $artist->country }}</span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-calendar ti-lg'></i>
                                        <span class="fw-medium">
                                            Joined {{ $artist->created_at->format('F Y') }}
                                        </span>
                                    </li>
                                </ul>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a class="nav-link " href="profile-artist"><i
                                class='ti-sm ti ti-user-check me-1_5'></i> {{__('Profile')}}</a></li>
                    {{-- <li class="nav-item"><a class="nav-link " href="{{ url('artist/profile-exhibition') }}"><i
                                class='ti-sm ti ti-presentation-analytics me-1_5'></i> {{__('Exhibitions')}}</a></li> --}}
                    <li class="nav-item"><a class="nav-link active" href="{{ url('artist/profile-artworks') }}"><i
                                class='ti-sm ti ti-palette me-1_5'></i> {{__('Artworks')}}</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-events') }}"><i
                                class='ti-sm ti ti-calendar me-1_5'></i> {{__('Events')}}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-awards') }}"><i
                                class='ti-sm ti ti-award me-1_5'></i> {{__('Awards')}}</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-collections') }}"><i
                                class='ti-sm ti ti-apps me-1_5'></i> {{__('Collections')}}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- Teams Cards -->
    <div class="row">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <!-- Total Artworks -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">{{__('Total Artworks')}}</p>
                                    <h4 class="mb-1">{{ $stats['totalArtworks'] }}</h4>
                                    <p class="mb-0">
                                        <span class="me-2">{{ $stats['activeArtworks'] }} {{__('active')}}</span>
                                        <span class="badge bg-label-success">+{{ $stats['artworkGrowth'] }}%</span>
                                    </p>
                                </div>
                                <span class="avatar me-sm-6">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ti-28px ti ti-palette text-primary"></i>
                                    </span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6">
                        </div>

                        <!-- Artwork Categories -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">{{__('Categories')}}</p>
                                    <h4 class="mb-1">{{ $stats['totalCategories'] }}</h4>
                                    <p class="mb-0">
                                        <span class="me-2">{{ $stats['mostPopularCategory'] }}</span>
                                        <span class="badge bg-label-info">{{__('Most popular')}}</span>
                                    </p>
                                </div>
                                <span class="avatar p-2 me-lg-6">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="ti-28px ti ti-category text-info"></i>
                                    </span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>

                        <!-- Featured Artworks -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">{{__('Featured')}}</p>
                                    <h4 class="mb-1">{{ $stats['featuredCount'] }}</h4>
                                    <p class="mb-0">{{ $stats['featuredViews'] }} {{__('views')}}</p>
                                </div>
                                <span class="avatar p-2 me-sm-6">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ti-28px ti ti-star text-warning"></i>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">{{__('Recent Activity')}}</p>
                                    <h4 class="mb-1">{{ $stats['recentUploads'] }}</h4>
                                    <p class="mb-0">
                                        <span class="me-2">{{__('last 7 days')}}</span>
                                        <span class="badge bg-label-success">+{{ $stats['uploadTrend'] }}%</span>
                                    </p>
                                </div>
                                <span class="avatar p-2">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ti-28px ti ti-cloud-upload text-success"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product List Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{__('Filter')}}</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-6 gap-md-0">
                    <div class="col-md-4 product_status"></div>
                    <div class="col-md-4 product_category"></div>
                    <div class="col-md-4 product_stock"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-products table">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{__('Title')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Medium')}}</th>
                            <th>{{__('Dimensions')}}</th>
                            <th>{{__('Year')}}</th>
                            <th>{{__('Price')}}</th>
                            <th>{{__('status')}}</th>
                            <th>{{__('actions')}}</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
@endsection


@section('scripts')

@endsection
