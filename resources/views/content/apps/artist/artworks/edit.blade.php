@extends('layouts/layoutMaster')

@section('title', 'Artwork Edit - Apps')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/tagify/tagify.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/tagify/tagify.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/artwork-add.js'])
@endsection

@section('content')
    <div class="app-ecommerce">
        <form action="{{ route('artist-artworks-update', $artwork->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Add Product -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Artwork</h4>
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
                                <input type="text" class="form-control" name="title"
                                    value="{{ old('title', $artwork->title) }}" required>
                            </div>
                            <div class="mb-6">
                                <label class="form-label">Category*</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($artwork) && $artwork->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="row mb-6">
                                <div class="col-md-4">
                                    <label class="form-label">Dimensions*</label>
                                    <input type="text" class="form-control" name="dimensions"
                                        value="{{ old('dimensions', $artwork->dimensions) }}" placeholder="90 x 60 cm"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Medium*</label>
                                    <input type="text" class="form-control" name="medium"
                                        value="{{ old('medium', $artwork->medium) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Year*</label>
                                    <input type="number" class="form-control" name="year_created"
                                        value="{{ old('year_created', $artwork->year) }}" min="1000"
                                        max="{{ date('Y') }}" required>
                                </div>
                            </div>

                            <!-- Pricing & Status -->
                            <div class="row mb-6">
                                <div class="col-md-4">
                                    <label class="form-label">Price (SAR)*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">SAR</span>
                                        <input type="number" class="form-control" name="price"
                                            value="{{ old('price', $artwork->price) }}" min="0" step="0.01"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status*</label>
                                    <select class="form-select" name="status" required>
                                        <option value="2" {{ $artwork->status == 2 ? 'selected' : '' }}>Available/For
                                            Sale</option>
                                        <option value="3" {{ $artwork->status == 3 ? 'selected' : '' }}>Sold</option>
                                        <option value="1" {{ $artwork->status == 1 ? 'selected' : '' }}>Not available
                                        </option>
                                        <option value="4" {{ $artwork->status == 4 ? 'selected' : '' }}>Not for sale
                                        </option>
                                        <option value="5" {{ $artwork->status == 5 ? 'selected' : '' }}>Details
                                            Pending</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Location *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="current_location" value="{{$artwork->current_location}}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="mb-6">
                                <label class="form-label">Artwork Images</label>
                                <!-- Current Images Gallery -->
                                @if ($artwork->images && count(json_decode($artwork->images)))
                                    <div class="mb-4">
                                        <h6 class="mb-3">Current Images</h6>
                                        <div class="row g-3">
                                            @foreach (json_decode($artwork->images) as $index => $image)
                                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                                    <div class="card image-card h-100">
                                                        <div class="card-img-top ratio ratio-1x1">
                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                class="img-fluid object-fit-cover"
                                                                alt="Artwork image {{ $index + 1 }}">
                                                        </div>
                                                        <div class="card-footer p-2 bg-light">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">Image {{ $index + 1 }}</small>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger btn-icon rounded-circle"
                                                                    onclick="removeImage(this, '{{ $image }}')"
                                                                    data-bs-toggle="tooltip" title="Remove image">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-4">
                                        <h6 class="mb-3">Current Images</h6>
                                        <div class="row g-3">
                                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                                <div class="card image-card h-100">
                                                    <div class="card-img-top ratio ratio-1x1">
                                                        <img src="{{ asset('storage/' . $artwork->image_path) }}"
                                                            class="img-fluid object-fit-cover" alt="Artwork image ">
                                                    </div>
                                                    <div class="card-footer p-2 bg-light">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">Image </small>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger btn-icon rounded-circle"
                                                                onclick="removeImage(this, '{{ $artwork->image_path }}')"
                                                                data-bs-toggle="tooltip" title="Remove image">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                                <small class="text-muted">Or enter image URL:</small>
                                <input type="url" class="form-control mt-2" name="image_url"
                                    placeholder="https://..." value="{{ old('image_url') }}">
                            </div>

                            <!-- Additional Information -->
                            <div class="mb-6">
                                <label class="form-label">Condition Report</label>
                                <select class="form-select" name="condition">
                                    <option value="Excellent" {{ $artwork->condition == 'Excellent' ? 'selected' : '' }}>
                                        Excellent</option>
                                    <option value="Good"
                                        {{ $artwork->condition == 'Good' || !$artwork->condition ? 'selected' : '' }}>Good
                                    </option>
                                    <option value="Fair" {{ $artwork->condition == 'Fair' ? 'selected' : '' }}>Fair
                                    </option>
                                    <option value="Poor" {{ $artwork->condition == 'Poor' ? 'selected' : '' }}>Poor
                                    </option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Provenance</label>
                                <textarea class="form-control" name="provenance" rows="2">{{ old('provenance', $artwork->provenance) }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Additional Comments</label>
                                <textarea class="form-control" name="comment" rows="3">{{ old('comment', $artwork->comment) }}</textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Artwork</button>
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
                                    @php
                                        $additionalInfo = is_string($artwork->additional_info)
                                            ? json_decode($artwork->additional_info, true)
                                            : $artwork->additional_info;
                                    @endphp
                                    @if ($additionalInfo && count($additionalInfo))
                                        @foreach ($additionalInfo as $info)
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-6 col-4">
                                                        <label class="form-label">Options</label>
                                                        <select name="additions" class="select2 form-select">
                                                            <option value="">Select option</option>
                                                            <option value="height"
                                                                {{ $info['option'] == 'height' ? 'selected' : '' }}>Height
                                                            </option>
                                                            <option value="framed"
                                                                {{ $info['option'] == 'framed' ? 'selected' : '' }}>Framed
                                                            </option>
                                                            <option value="weight"
                                                                {{ $info['option'] == 'weight' ? 'selected' : '' }}>Weight
                                                            </option>
                                                            <option value="width"
                                                                {{ $info['option'] == 'width' ? 'selected' : '' }}>Width
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-6 col-8">
                                                        <label class="form-label">Value</label>
                                                        <input type="text" name="additions_value" class="form-control"
                                                            placeholder="Enter value" value="{{ $info['value'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div data-repeater-item>
                                            <div class="row">
                                                <div class="mb-6 col-4">
                                                    <label class="form-label">Options</label>
                                                    <select name="additions" class="select2 form-select">
                                                        <option value="">Select option</option>
                                                        <option value="size">Size</option>
                                                        <option value="color">Color</option>
                                                        <option value="weight">Weight</option>
                                                        <option value="smell">Smell</option>
                                                    </select>
                                                </div>
                                                <div class="mb-6 col-8">
                                                    <label class="form-label">Value</label>
                                                    <input type="text" name="additions_value" class="form-control"
                                                        placeholder="Enter value">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
@section('scripts')
    <script>
        function removeImage(button, imagePath) {
            if (confirm('Are you sure you want to remove this image?')) {
                // Create hidden input to track removed images
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'removed_images[]';
                input.value = imagePath;
                button.closest('form').appendChild(input);

                // Remove the image element
                button.closest('.image-card').remove();
            }
        }
    </script>
@endsection
