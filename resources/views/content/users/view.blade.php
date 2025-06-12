@extends('layouts/layoutMaster')

@section('title', 'Artwork Details')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="artwork-display">
            <!-- Artist Header -->
            <div class="artist-header mb-4">
                <h2 class="artist-name">{{ $artwork->artist->name }}</h2>
                <div class="artist-divider"></div>
            </div>

            <div class="card artwork-card">
                <div class="row g-0">
                    <!-- Artwork Frame Column -->
                    <div class="col-md-7">
                        @php
                            $imagePaths = json_decode($artwork->image_path, true);
                            $mainImage = $imagePaths[0] ?? null;
                        @endphp

                        @if ($mainImage)
                            <div class="ornate-frame medium" id="artworkFrame">
                                <div class="artwork-mat">
                                    <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $artwork->title }}"
                                        class="artwork-image">
                                </div>
                            </div>
                        @else
                            <p>No image available</p>
                        @endif


                        <!-- Size Selector -->
                        <div class="size-controls mt-3 text-center">
                            <div class="btn-group btn-group-sm size-selector">
                                <button type="button" class="btn btn-outline-dark active" data-size="small">Small</button>
                                <button type="button" class="btn btn-outline-dark" data-size="medium">Medium</button>
                                <button type="button" class="btn btn-outline-dark" data-size="large">Large</button>
                                <button type="button" class="btn btn-outline-dark" data-size="full">Full Width</button>
                            </div>
                        </div>
                    </div>

                    <!-- Artwork Details Column -->
                    <div class="col-md-5">
                        <div class="card-body artwork-details">
                            <h1 class="artwork-title">{{ $artwork->title }}</h1>

                            <div class="artwork-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Medium:</span>
                                    <span class="meta-value">{{ $artwork->medium }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Dimensions:</span>
                                    <span class="meta-value">{{ $artwork->dimensions }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Year:</span>
                                    <span class="meta-value">{{ $artwork->year }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Source:</span>
                                    <span class="meta-value">{{ $artwork->source }}</span>
                                </div>

                                <div class="meta-item">
                                    <span class="meta-label">Provenance:</span>
                                    <span class="meta-value">{{ $artwork->provenance }}</span>
                                </div>
                                 <div class="meta-item">
                                    <span class="meta-label">Location:</span>
                                    <span class="meta-value">{{ $artwork->current_location }}</span>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Artist Header */
        .artist-header {
            text-align: center;
            padding-bottom: 1rem;
        }

        .artist-name {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #333;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .artist-divider {
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #8B4513, #e6d5b8);
            margin: 0 auto;
        }

        /* Artwork Card */
        .artwork-card {
            border: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Artwork Details */
        .artwork-details {
            padding: 2rem;
        }

        .artwork-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: #222;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .artwork-meta {
            background: #f9f9f9;
            padding: 1.25rem;
            border-radius: 8px;
        }

        .meta-item {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .meta-item:last-child {
            margin-bottom: 0;
        }

        .meta-label {
            font-weight: 600;
            color: #555;
            min-width: 100px;
        }

        .meta-value {
            color: #333;
        }

        .description-title {
            font-weight: 600;
            color: #444;
            margin-bottom: 1rem;
        }

        .description-text {
            color: #555;
            line-height: 1.6;
        }

        /* Artist Info Section */
        .artist-info {
            background: #f9f9f9;
            padding: 1.25rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .artist-info-title {
            font-weight: 600;
            color: #444;
            margin-bottom: 1rem;
        }

        .artist-bio {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        /* CV Download Button */
        .cv-download .btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Frame Styles */
        .ornate-frame {
            position: relative;
            background: #e6d5b8;
            margin: 20px auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .artwork-mat {
            background: #f8f5f0;
            padding: 15px;
            border: 1px solid #e0d6c2;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .artwork-image {
            display: block;
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            transition: transform 0.3s ease;
        }

        .ornate-frame::before,
        .ornate-frame::after {
            content: "";
            position: absolute;
            border: 3px solid #8B4513;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .ornate-frame::before {
            top: 15px;
            left: 15px;
            border-right: none;
            border-bottom: none;
        }

        .ornate-frame::after {
            bottom: 15px;
            right: 15px;
            border-left: none;
            border-top: none;
        }

        /* Size Variations */
        .ornate-frame.small {
            padding: 15px;
            max-width: 300px;
        }

        .ornate-frame.medium {
            padding: 25px;
            max-width: 500px;
        }

        .ornate-frame.large {
            padding: 35px;
            max-width: 700px;
        }

        .ornate-frame.full {
            padding: 20px;
            width: 100%;
            max-width: 100%;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .artwork-card .row {
                flex-direction: column;
            }

            .ornate-frame {
                margin: 0 auto 2rem auto;
            }

            .artwork-details {
                padding: 1.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizeButtons = document.querySelectorAll('.size-selector .btn');
            const artworkFrame = document.getElementById('artworkFrame');

            sizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    sizeButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Update frame size
                    const size = this.dataset.size;
                    artworkFrame.className = 'ornate-frame ' + size;
                });
            });
        });
    </script>
@endsection
