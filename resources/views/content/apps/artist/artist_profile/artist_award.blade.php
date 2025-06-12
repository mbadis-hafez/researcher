@extends('layouts/layoutMaster')

@section('title', 'User Profile - Awards')

<!-- Page -->
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
                            <button type="button" class="btn btn-primary btn-add-award">
                                <i class="ti ti-plus me-2"></i>{{ trans('Add New Award') }}
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-exhibition') }}"><i
                                class='ti-sm ti ti-presentation-analytics me-1_5'></i> Exhibitions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-artworks') }}"><i
                                class='ti-sm ti ti-palette me-1_5'></i> Artworks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-events') }}"><i
                                class='ti-sm ti ti-calendar me-1_5'></i> Events</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('artist/profile-awards') }}"><i
                                class='ti-sm ti ti-award me-1_5'></i> Awards</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('artist/profile-collections') }}"><i
                                class='ti-sm ti ti-apps me-1_5'></i> Collections</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- Awards Cards -->
    <div class="row">
        @forelse ($awards as $award)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $award->name }}</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="awardActions" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="awardActions">
                                <button class="dropdown-item btn-edit-award" 
                                        data-id="{{ $award->id }}"
                                        data-award='@json([
                                            'name' => $award->name,
                                            'date' => $award->date->format('Y-m-d'),
                                            'description' => $award->description
                                        ])'>
                                    <i class="ti ti-edit me-1"></i>Edit
                                </button>
                                <li>
                                        <form class="delete-confirmation" method="POST" action="{{ route('artists.awards.destroy', [$artist->id, $award
                                        ->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                              <i class="ti ti-trash me-2"></i> {{ trans('Delete') }}
                                            </button>
                                        </form>
                                    </li>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="timeline mb-0">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Award Received</h6>
                                        <small class="text-muted">{{ $award->date->format('M d, Y') }}</small>
                                    </div>
                                    @if($award->description)
                                    <p class="mb-0">
                                        {{ $award->description }}
                                    </p>
                                    @endif
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Added to Profile</h6>
                                        <small class="text-muted">{{ $award->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <span class="alert-icon"><i class="ti ti-alert-circle"></i></span>
                    {{ trans('No awards found for this artist') }}
                </div>
            </div>
        @endforelse
    </div>

    <!-- Add/Edit Award Modal -->
    <div class="modal fade" id="awardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ trans('Add New Award') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="awardForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="method-field"></div> <!-- For PUT method on updates -->
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Award Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ trans('Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ trans('Description') }}</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('awardModal'));
        const form = document.getElementById('awardForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('method-field');

        // Add new award button
        document.querySelector('.btn-add-award').addEventListener('click', function() {
            form.reset();
            modalTitle.textContent = 'Add New Award';
            form.action = "{{ route('artists.awards.store', $artist) }}";
            methodField.innerHTML = '';
            modal.show();
        });

        // Edit award buttons
        document.querySelectorAll('.btn-edit-award').forEach(button => {
            button.addEventListener('click', function() {
                const awardId = this.dataset.id;
                const awardData = this.dataset.award;

                try {
                    const award = JSON.parse(awardData);
                    
                    // Fill the form with existing data
                    document.getElementById('name').value = award.name;
                    document.getElementById('date').value = award.date;
                    document.getElementById('description').value = award.description || '';

                    // Set form action for update
                    form.action = "{{ route('artists.awards.update', [$artist, 'award_id']) }}"
                        .replace('award_id', awardId);

                    // Add PUT method field
                    methodField.innerHTML = '@method('PUT')';

                    modalTitle.textContent = 'Edit Award';
                    modal.show();
                } catch (e) {
                    console.error('Error parsing award data:', e);
                }
            });
        });
    });
</script>
@endsection