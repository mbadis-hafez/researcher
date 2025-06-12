@extends('layouts/layoutMaster')

@section('title', 'Artist Profile - Profile')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Flag Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css" />
    @vite(['resources/assets/vendor/scss/pages/page-profile.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
        'resources/assets/vendor/libs/select2/select2.scss',])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/pages-profile.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
        'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
        'resources/assets/vendor/libs/moment/moment.js',
        'resources/assets/vendor/libs/flatpickr/flatpickr.js',])
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
                                            {{ __(@ucfirst($artist->user->getRoleNames()->first())) }}
                                        </span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-map-pin ti-lg'></i><span
                                            class="fw-medium">{{ $artist->country }}</span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-calendar ti-lg'></i>
                                        <span class="fw-medium">
                                            {{ __('Dashboards') }} {{ $artist->created_at->format('F Y') }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-primary btn-update-profile">
                                <i class="ti ti-plus me-2"></i>{{ trans( 'Update My Information') }}
                            </button>
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
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                class='ti-sm ti ti-user-check me-1_5'></i> {{ __('Profile') }}</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-exhibition') }}"><i
                                class='ti-sm ti ti-presentation-analytics me-1_5'></i> {{ __('Exhibitions') }}</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-artworks') }}"><i
                                class='ti-sm ti ti-palette me-1_5'></i> {{ __('Artworks') }}</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-events') }}"><i
                                class='ti-sm ti ti-calendar me-1_5'></i> {{ __('Events') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-awards') }}"><i
                                class='ti-sm ti ti-award me-1_5'></i> {{ __('Awards') }}</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-collections') }}"><i
                                class='ti-sm ti ti-apps me-1_5'></i> {{ __('Collections') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">{{ __('About') }}</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-user ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Full Name') }}:</span>
                            <span>{{ @ucfirst($artist->name) }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-calendar ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Birth Date') }}:</span>
                            <span>{{ @$artist->artist_date }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-crown ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Role') }}:</span> <span>
                                {{ ucfirst($artist->user->getRoleNames()->first()) }}</span></li>
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-flag ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Country') }}:</span> <span>{{ @$artist->country }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-2"><i class="ti ti-language ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Languages') }}:</span> <span>English</span></li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">{{ __('Contacts') }}</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-phone-call ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Contact') }}:</span>
                            <span>{{ @$artist->phone_number ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-messages ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Website') }}:</span>
                            <span>{{ @$artist->website ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4"><i class="ti ti-mail ti-lg"></i><span
                                class="fw-medium mx-2">{{ __('Email') }}:</span>
                            <span>{{ @$artist->email ?? 'N/A' }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">{{ __('Exhibitions') }}</small>
                    <ul class="list-unstyled mb-0 mt-3 pt-1">
                        @if ($artist->exhibitions && count($artist->exhibitions))
                            @foreach ($artist->exhibitions as $exhibition)
                                <li class="d-flex flex-wrap mb-4">
                                    <span class="fw-medium me-2">{{ $exhibition->name }}</span>
                                    <span>{{ $exhibition->date }}</span>
                                </li>
                            @endforeach
                        @else
                            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                <i class="ti ti-alert-circle ti-xs me-2"></i>
                                {{ trans('No exhibitions found for this artist') }}
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
            <!--/ About User -->
            <!-- Profile Overview -->
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">{{ __('Overview') }}</small>
                    <ul class="list-unstyled mb-0 mt-3 pt-1">
                        <li class="d-flex align-items-end mb-4">
                            <i class="ti ti-palette ti-lg"></i>
                            <span class="fw-medium mx-2">{{ __('Artworks') }}:</span>
                            <span>{{ count($artist->artworks) ?? '0' }}</span>
                        </li>
                        <li class="d-flex align-items-end mb-4">
                            <i class="ti ti-photo ti-lg"></i>
                            <span class="fw-medium mx-2">{{ __('Exhibitions') }}:</span>
                            <span>{{ $artist->exhibitions->count() ?? '0' }}</span>
                        </li>
                        <li class="d-flex align-items-end mb-4">
                            <i class="ti ti-award ti-lg"></i>
                            <span class="fw-medium mx-2">{{ __('Awards') }}:</span>
                            <span>{{ $artist->awards->count() ?? '0' }}</span>
                        </li>
                        <li class="d-flex align-items-end">
                            <i class="ti ti-apps ti-lg"></i>
                            <span class="fw-medium mx-2">{{ __('Collections') }}:</span>
                            <span>{{ $artist->collections->count() ?? '0' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/ Profile Overview -->
        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">
            <!-- Activity Timeline -->
            <div class="card card-action mb-6">
                <div class="card-header align-items-center bg-light-primary">
                    <h5 class="card-action-title mb-0">
                        <i class='ti ti-book-2 ti-lg me-3'></i>{{ __('Artist Biography') }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Short Biography Section -->
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="ti ti-sparkles text-warning me-2"></i>
                                <span>{{ __('Artist Statement') }}</span>
                            </h6>
                        </div>
                        <div class="ps-4">
                            <p class="mb-0 text-body">
                                {{ $artist->short_biography ?? 'No artist statement available' }}
                            </p>
                        </div>
                    </div>

                    <!-- Full Biography Section -->
                    <div class="pt-2">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="ti ti-notebook text-primary me-2"></i>
                                <span>{{ __('Full Biography') }}</span>
                            </h6>
                        </div>
                        <div class="ps-4">
                            <div class="text-body">
                                {!! nl2br(e($artist->full_biography ?? 'No biography details available')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Activity Timeline -->
            {{-- <div class="row">
                <!-- Exhibitions -->
                <div class="col-lg-12 col-xl-6">
                    <div class="card card-action mb-6">
                        <div class="card-header align-items-center">
                            <h5 class="card-action-title mb-0">{{ trans('Exhibitions') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($artist->exhibitions->take(5) as $exhibition)
                                    <li class="mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <img src="{{ asset('storage/' . $exhibition->image) }}"
                                                        alt="Avatar" class="rounded-circle" />
                                                </div>
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $exhibition->name }}</h6>
                                                    <small>{{ $exhibition->location }}</small>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <span class="badge bg-{{ $exhibition->status_color }} m-2">
                                                    {{ $exhibition->status_text }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                        <i class="ti ti-alert-circle ti-xs me-2"></i>
                                        {{ trans('No exhibitions found for this artist') }}
                                    </div>
                                @endforelse
                                <li class="text-center">
                                    <a href="{{ route('artist-exhibition') }}">{{ trans('View all exhibitions') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/ Exhibitions -->
                <!-- Events -->
                {{-- <div class="col-lg-12 col-xl-6">
                    <div class="card card-action mb-6">
                        <div class="card-header align-items-center">
                            <h5 class="card-action-title mb-0">{{ trans('Events') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($artist->events->take(5) as $event)
                                    <li class="mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Avatar"
                                                        class="rounded-circle" />
                                                </div>
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $event->name }}</h6>
                                                    <small>{{ $event->location }}</small>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <span class="badge bg-{{ $event->status_color }} m-2">
                                                    {{ $event->status_text }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                        <i class="ti ti-alert-circle ti-xs me-2"></i>
                                        {{ trans('No events found for this artist') }}
                                    </div>
                                @endforelse
                                <li class="text-center">
                                    <a href="{{ route('artist-events') }}">{{ trans('View all events') }} </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
                <!--/ Events -->
            </div> --}}
        </div>
    </div>
    <!--/ User Profile Content -->
    <!-- Update Information Modal -->
    <div class="modal fade" id="updateInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ trans('Update Personal Information') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateInfoForm" action="{{ route('app-artist-update', $artist->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-6">
                                <img src="{{ asset($artist->image) }}" alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="ti ti-upload d-block d-sm-none"></i>
                                        <input type="file" name='image' id="upload" class="account-file-input"
                                            hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                        <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>

                                    <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-name">{{ trans('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" required id="formtabs-name" class="form-control"
                                    value="{{ $artist->name }}" name="name" placeholder="John" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-last-email">{{ trans('Email') }} </label>
                                <input type="email" value="{{ $artist->email }}" name="email"
                                    id="formtabs-last-email" class="form-control" placeholder="john@gmail.com" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-last-name">{{ trans('Password') }} </label>
                                <input type="password" name="password" id="formtabs-last-name" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-birthdate">{{ trans('Artist Dates') }}
                                </label>
                                <input type="text" value="{{ $artist->artist_date }}" name="artist_date"
                                    id="formtabs-birthdate" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-description">{{ trans('Description') }}
                                </label>
                                <textarea type="text" name="description" id="formtabs-description" class="form-control"
                                    placeholder="description">{{ $artist->description }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-short-biography">{{ trans('Short Biography') }}
                                </label>
                                <textarea name="short_biography" id="formtabs-short-biography" class="form-control" placeholder="Short Biography">{{ $artist->short_biography }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-full-biography">{{ trans('Full Biography') }}
                                </label>
                                <textarea name="full_biography" id="formtabs-full-biography" class="form-control" placeholder="Full Biography">{{ $artist->full_biography }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-website">{{ trans('Artist Website') }}
                                </label>
                                <input type="text" value="{{ $artist->website }}" id="formtabs-website"
                                    class="form-control" name="website" placeholder="https://*" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-notes">{{ trans('Notes') }} </label>
                                <textarea name="notes" id="formtabs-notes" class="form-control" placeholder="Notes">{{ $artist->notes }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="multicol-country">{{ trans('Country') }} </label>
                                <select id="multicol-country" name="country" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">{{ trans('Select') }}</option>
                                    @foreach ($countries as $key => $country)
                                        @php
                                            $country = country($key);
                                        @endphp
                                        <option @if ($artist->country == $country->getIsoAlpha2()) selected @endif
                                            value="{{ $country->getIsoAlpha2() }}"
                                            data-flag="{{ strtolower($country->getIsoAlpha2()) }}">
                                            {{ $country->getName() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="btn btn-primary me-4">{{ trans('Submit') }} </button>
                            <button type="reset" class="btn btn-label-secondary">{{ trans('Cancel') }}
                            </button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function formatCountry(option) {
            if (!option.id) return option.text;
            const flag = $(option.element).data('flag');
            const countryName = option.text;
            return $(`<span><span class="fi fi-${flag} me-2"></span> ${countryName}</span>`);
        }

 $('.select2').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
            });
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
           

            // Initialize modal
            const updateInfoModal = new bootstrap.Modal(document.getElementById('updateInfoModal'));
            
            // Show modal when update button is clicked
            document.querySelector('.btn-update-profile').addEventListener('click', function() {
                updateInfoModal.show();
            });

            // Image upload preview functionality
            const uploadedAvatar = document.getElementById('uploadedAvatar');
            const accountFileInput = document.querySelector('.account-file-input');
            
            if (accountFileInput) {
                accountFileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            uploadedAvatar.src = event.target.result;
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }

            // Reset image button
            const accountImageReset = document.querySelector('.account-image-reset');
            if (accountImageReset) {
                accountImageReset.addEventListener('click', function() {
                    uploadedAvatar.src = "{{ asset($artist->image) }}";
                    accountFileInput.value = '';
                });
            }
        });
    </script>
@endsection
