@extends('layouts/layoutMaster')

@section('title', 'Artwork Add - Apps')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/tagify/tagify.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/tagify/tagify.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/app-ecommerce-product-add.js'])
@endsection

@section('content')
    <div class="app-ecommerce">
        <form action="{{ route('artist-artworks-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Add Product -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new Artwork</h4>
                </div>
            </div>

            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Artwork Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Artwork Details -->
                            <div class="mb-6">
                                <label class="form-label">Title*</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Category*</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row mb-6">
                                <div class="col-md-4">
                                    <label class="form-label">Dimensions*</label>
                                    <input type="text" class="form-control" name="dimensions" placeholder="90 x 60 cm"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Medium*</label>
                                    <input type="text" class="form-control" name="medium" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Year*</label>
                                    <input type="number" class="form-control" name="year_created" min="1000"
                                        max="{{ date('Y') }}" required>
                                </div>
                            </div>

                            <!-- Pricing & Status -->
                            <div class="row mb-6">
                                <div class="col-md-4">
                                    <label class="form-label">Price (SAR)*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">SAR</span>
                                        <input type="number" class="form-control" name="price" min="0"
                                            step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status*</label>
                                    <select class="form-select" name="status" required>
                                        <option value="2" selected>Available/For Sale</option>
                                        <option value="3">Sold</option>
                                        <option value="1">Not available</option>
                                        <option value="4">Not for sale</option>
                                        <option value="5">Details Pending</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Location *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="current_location" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="mb-6">
                                <label class="form-label">Artwork Images*</label>
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*"
                                    required>
                                <small class="text-muted">Or enter image URL:</small>
                                <input type="url" class="form-control mt-2" name="image_url" placeholder="https://...">
                            </div>

                            <!-- Additional Information -->
                            <div class="mb-6">
                                <label class="form-label">Condition Report</label>
                                <select class="form-select" name="condition">
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good" selected>Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Provenance</label>
                                <textarea class="form-control" name="provenance" rows="2"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Additional Comments</label>
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Artwork</button>
                            </div>
                        </div>
                    </div>
                    <!-- /Product Information -->
                </div>
                <!-- /Second column -->

                <!-- Second column -->
                <div class="col-12 col-lg-4">

                    <!-- Variants -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Additional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-repeater">
                                <div data-repeater-list="group-a">
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="mb-6 col-4">
                                                <label class="form-label">Options</label>
                                                <select name="additions" class="select2 form-select">
                                                    <option value="">Select option</option>
                                                    <option value="height">Height</option>
                                                    <option value="framed">Framed</option>
                                                    <option value="weight">Weight</option>
                                                    <option value="width">Width</option>

                                                </select>
                                            </div>
                                            <div class="mb-6 col-8">
                                                <label class="form-label">Value</label>
                                                <input type="text" name="additions_value" class="form-control"
                                                    placeholder="Enter value">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" data-repeater-create type="button">
                                    <i class='ti ti-plus ti-xs me-2'></i>
                                    Add another option
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /Variants -->
                </div>
                <!-- /Second column -->
            </div>
        </form>

    </div>

@endsection
