@extends('layouts/layoutMaster')

@section('title', 'User Profile - Events')

<!-- Page -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-profile.scss'])
@endsection
@section('page-script')
@vite('resources/assets/js/app-artist-event.js')
@endsection
@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;
@endphp

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
                                            {{ trans('Joined') }} {{ $artist->created_at->format('F Y') }}
                                        </span>
                                    </li>
                                </ul>

                            </div>
                            <button type="button" class="btn btn-primary btn-add-event">
                                <i class="ti ti-plus me-2"></i>{{ trans('Add New Event') }}
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
                    <li class="nav-item"><a class="nav-link " href="profile-artist"><i
                                class='ti-sm ti ti-user-check me-1_5'></i> Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-exhibition') }}"><i
                                class='ti-sm ti ti-presentation-analytics me-1_5'></i> Exhibitions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-artworks') }}"><i
                                class='ti-sm ti ti-palette me-1_5'></i> Artworks</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('artist/profile-events') }}"><i
                                class='ti-sm ti ti-calendar me-1_5'></i> Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-awards') }}"><i
                                class='ti-sm ti ti-award me-1_5'></i> Awards</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-collections') }}"><i
                                class='ti-sm ti ti-apps me-1_5'></i> Collections</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- Teams Cards -->
    <div class="row">

        @forelse ($events as $event)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <!-- Status Ribbon -->
                    <span class="badge bg-{{ $event->status_color }} position-absolute top-0 end-0 m-2 z-3" style="z-index: 1;">
                        {{ $event->status_text }}
                    </span>
                    <!-- Event Image -->
                    @if ($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="card-img-top"
                            alt="{{ $event->name }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                            style="height: 180px;">
                            <i class="ti ti-photo ti-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $event->name }}</h5>
                                <span class="badge bg-label-primary"> <i class="ti ti-map-pin me-1"></i>
                                    {{ $event->location }}</span>
                            </div>
                            <div class="dropdown">
                                <button type="button"
                                    class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow p-0"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical ti-md text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        @php
                                            $data = json_encode([
                                                'name' => $event->name,
                                                'start_date' => $event->start_date->format('Y-m-d'),
                                                'end_date' => $event->end_date->format('Y-m-d'),
                                                'location' => $event->location,
                                                'description' => $event->description,
                                                'image' => $event->image,
                                                'image_url' => $event->image
                                                    ? Storage::url($event->image)
                                                    : null,
                                            ]);

                                        @endphp
                                        <a class="dropdown-item btn-edit-event" href="#"
                                            data-id="{{ $event->id }}"data-event="{{ $data }}">
                                            <i class="ti ti-pencil me-2"></i>{{ trans('Edit Event') }} 
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#eventModal{{ $event->id }}">
                                            <i class="ti ti-eye me-2"></i>{{ trans('View Details') }} 
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form class="delete-confirmation" method="POST" action="{{ route('artists.events.destroy', [$artist->id, $event->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                              <i class="ti ti-trash me-2"></i> {{ trans('Delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="card-text text-muted mb-4">{{ $event->short_description }}</p>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <small class="text-muted">{{ trans('Start Date') }} </small>
                                <p class="mb-0 fw-semibold">{{ $event->start_date->format('Y-m-d') }}</p>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ trans('End Date') }} </small>
                                <p class="mb-0 fw-semibold">{{ @$event->end_date->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Event Details -->
            <div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $event->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    @if ($event->image)
                                        <img src="{{ Storage::url($event->image) }}" class="img-fluid rounded"
                                            alt="{{ $event->name }}">
                                    @endif
                                    <h6 class="mt-4 mb-3">{{ trans('Description') }}</h6>
                                    <p>{{ $event->description }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">{{ trans('Event Details') }} </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <span class="fw-medium me-2">{{ trans('Location') }}:</span>
                                            {{ $event->location }}
                                        </li>
                                        <li class="mb-2">
                                            <span class="fw-medium me-2">{{ trans('Dates') }}:</span>
                                            {{ $event->start_date }} - {{ $event->end_date }}
                                        </li>
                                        <li class="mb-2">
                                            <span class="fw-medium me-2">{{ trans('Artist') }}:</span>
                                            {{ $artist->name }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ trans('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <span class="alert-icon"><i class="ti ti-alert-circle"></i></span>
                    {{ trans('No events found for this artist') }}
                    
                </div>
            </div>
        @endforelse
    </div>
    <!-- Add this at the bottom of your events/index.blade.php -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ trans('Add New Event') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="eventForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="method-field"></div> <!-- For PUT method on updates -->

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Event Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ trans('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ trans('End Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Location') }} <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Description') }}</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Image') }}</label>
                                <input type="file" name="image" id="image" class="form-control">
                                <div class="mt-2" id="image-preview"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ trans('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/ Teams Cards -->
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            const form = document.getElementById('eventForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('method-field');
            const imagePreview = document.getElementById('image-preview');

            // Add new event button
            document.querySelector('.btn-add-event').addEventListener('click', function() {
                form.reset();
                modalTitle.textContent = 'Add New Event';
                form.action = "{{ route('artists.events.store', $artist) }}";
                methodField.innerHTML = '';
                imagePreview.innerHTML = '';
                modal.show();
            });

            // Edit buttons - add this class to your edit buttons
            document.querySelectorAll('.btn-edit-event').forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.dataset.id;
                    console.log(eventId);
                    const event = JSON.parse(this.dataset.event);
                    console.log(event)

                    // Fill the form with existing data
                    document.getElementById('name').value = event.name;
                    document.getElementById('start_date').value = event.start_date;
                    document.getElementById('end_date').value = event.end_date;
                    document.getElementById('location').value = event.location;
                    document.getElementById('description').value = event.description;

                    // Set form action for update
                    form.action =
                        "{{ route('artists.events.update', [$artist, 'event_id']) }}"
                        .replace('event_id', eventId);

                    // Add PUT method field
                    methodField.innerHTML = '@method('PUT')';

                    // Show image preview if exists
                    if (event.image_url) {
                        imagePreview.innerHTML = `
                    <p>Current Image:</p>
                    <img src="${event.image_url}" class="img-thumbnail" width="150">
                    <input type="hidden" name="current_image" value="${event.image}">
                `;
                    } else {
                        imagePreview.innerHTML = '';
                    }

                    modalTitle.textContent = 'Edit Event';
                    modal.show();
                });
            });

            // Image preview
            document.getElementById('image').addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const src = URL.createObjectURL(e.target.files[0]);
                    imagePreview.innerHTML = `
                <p>New Image Preview:</p>
                <img src="${src}" class="img-thumbnail" width="150">
            `;
                }
            });
        });
    </script>
@endsection
