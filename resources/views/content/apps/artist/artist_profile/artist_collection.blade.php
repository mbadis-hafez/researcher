@extends('layouts/layoutMaster')

@section('title', 'User Profile - Collections')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-profile.scss'])
@endsection

@section('page-script')
    @vite('resources/assets/js/app-artist-collection.js')
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
                            <button type="button" class="btn btn-primary btn-add-collection">
                                <i class="ti ti-plus me-2"></i>{{ trans('Add New Collection') }}
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
                    <li class="nav-item"><a class="nav-link" href="profile-artist"><i
                                class='ti-sm ti ti-user-check me-1_5'></i> Profile</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-exhibition') }}"><i
                                class='ti-sm ti ti-presentation-analytics me-1_5'></i> Exhibitions</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-artworks') }}"><i
                                class='ti-sm ti ti-palette me-1_5'></i> Artworks</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-events') }}"><i
                                class='ti-sm ti ti-calendar me-1_5'></i> Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-awards') }}"><i
                                class='ti-sm ti ti-award me-1_5'></i> Awards</a></li> --}}
                    <li class="nav-item"><a class="nav-link active" href="{{ url('artist/profile-collections') }}"><i
                                class='ti-sm ti ti-apps me-1_5'></i> Collections</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- Collections Cards -->
    <div class="row">
        @forelse ($collections as $collection)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $collection->name }}</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="collectionActions" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="collectionActions">
                                <button class="dropdown-item btn-edit-collection" data-id="{{ $collection->id }}"
                                    data-collection='@json([
                                        'name' => $collection->name,
                                        'artworks' => $collection->artworks->pluck('id')->toArray(),
                                    ])'>
                                    <i class="ti ti-edit me-1"></i>Edit
                                </button>
                                        <form class="delete-confirmation" method="POST" action="{{ route('artists.collections.destroy', [$artist->id,$collection->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                              <i class="ti ti-trash me-2"></i> {{ trans('Delete') }}
                                            </button>
                                        </form>
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse($collection->artworks as $artwork)
                                <div class="col-6">
                                    <div class="artwork-thumbnail position-relative">
                                        <img src="{{ Storage::url($artwork->image_path) }}" alt="{{ $artwork->name }}"
                                            class="img-fluid rounded mb-2"
                                            style="height: 120px; width: 100%; object-fit: cover;">
                                        <p class="mb-0 text-truncate">{{ $artwork->name }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">No artworks in this collection</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <span class="alert-icon"><i class="ti ti-alert-circle"></i></span>
                    {{ trans('No collections found') }}
                </div>
            </div>
        @endforelse
    </div>

    <!-- Add/Edit Collection Modal -->
    <div class="modal fade" id="collectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ trans('Add New Collection') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="collectionForm" method="POST">
                    @csrf
                    <div id="method-field"></div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Collection Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="collectionName" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Select Artworks') }}</label>
                                <div class="row g-3 artwork-selection">
                                    @foreach ($artworks as $artwork)
                                        <div class="col-6 col-md-4">
                                            <div class="form-check card card-border-shadow-primary">
                                                <input class="form-check-input" type="checkbox" name="artworks[]"
                                                    id="artwork-{{ $artwork->id }}" value="{{ $artwork->id }}">
                                                <label class="form-check-label w-100" for="artwork-{{ $artwork->id }}">
                                                    <img src="{{ Storage::url($artwork->image_path) }}"
                                                        alt="{{ $artwork->name }}" class="img-fluid rounded mb-2"
                                                        style="height: 100px; width: 100%; object-fit: cover;">
                                                    <p class="mb-0 text-center">{{ $artwork->name }}</p>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">{{ trans('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('collectionModal'));
            const form = document.getElementById('collectionForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('method-field');
            const collectionName = document.getElementById('collectionName');

            // Add new collection button
            document.querySelector('.btn-add-collection').addEventListener('click', function() {
                form.reset();
                modalTitle.textContent = 'Add New Collection';
                form.action = "{{ route('artists.collections.store', ['artist' => $artist->id]) }}";
                methodField.innerHTML = '';

                // Uncheck all checkboxes
                document.querySelectorAll('input[name="artworks[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                modal.show();
            });

            // Edit collection buttons
            document.querySelectorAll('.btn-edit-collection').forEach(button => {
                button.addEventListener('click', function() {
                    const collectionId = this.dataset.id;
                    const collectionData = this.dataset.collection;

                    try {
                        const collection = JSON.parse(collectionData);

                        // Fill the form with existing data
                        collectionName.value = collection.name;

                        // Uncheck all checkboxes first
                        document.querySelectorAll('input[name="artworks[]"]').forEach(checkbox => {
                            checkbox.checked = false;
                        });

                        // Check the artworks that belong to this collection
                        collection.artworks.forEach(artworkId => {
                            const checkbox = document.querySelector(
                            `#artwork-${artworkId}`);
                            if (checkbox) checkbox.checked = true;
                        });

                        // Set form action for update
                        form.action =
                            "{{ route('artists.collections.update', [$artist, 'collection_id']) }}"
                            .replace('collection_id', collectionId);

                        // Add PUT method field
                        methodField.innerHTML = '@method('PUT')';

                        modalTitle.textContent = 'Edit Collection';
                        modal.show();
                    } catch (e) {
                        console.error('Error parsing collection data:', e);
                    }
                });
            });

       });
    </script>
@endsection
