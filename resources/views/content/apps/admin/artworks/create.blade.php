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
        <form action="{{ route('admin.artworks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Add Product -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">{{__('Add a new Artwork')}}</h4>
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
                            <h5 class="card-title mb-0">{{__('Artwork Information')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">

                                <label class="form-label">{{__("Researcher")}}</label>
                                <div class="input-group">
                                    <select class="form-select" name="researcher_id" id="researcher-select">
                                        <option value="">{{__('Select researcher')}}</option>
                                        @foreach ($researchers as $researcher)
                                            <option value="{{ $researcher->id }}">{{ $researcher->name }}</option>
                                        @endforeach
                                        <option value="other">{{__('Other (not listed)')}}</option>
                                    </select>
                                </div>
                                <div class="mt-2" id="researcher-name-container" style="display:none;">
                                    <input type="text" class="form-control" name="researcher_name"
                                        id="researcher-name-input" placeholder="Enter researcher name">
                                </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const researcherSelect = document.getElementById('researcher-select');
                                            const researcherNameContainer = document.getElementById('researcher-name-container');
                                            const researcherNameInput = document.getElementById('researcher-name-input');

                                            researcherSelect.addEventListener('change', function() {
                                                if (this.value === 'other') {
                                                    researcherNameContainer.style.display = 'block';
                                                    researcherNameInput.required = true;
                                                } else {
                                                    researcherNameContainer.style.display = 'none';
                                                    researcherNameInput.required = false;
                                                }
                                            });
                                        });
                                    </script>
                            </div>
                            <!-- Artwork Details -->
                            <div class="mb-6">
                                <label class="form-label">{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title">
                            </div>

                            <div class="mb-6">
                                <label class="form-label">{{__('Artist*')}}</label>
                                <select class="form-select" name="artist_id" required>
                                    <option value="">{{__('Select artist')}}</option>
                                    @foreach ($artists as $artist)
                                        <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mb-6">
                                <div class="col-md-4">
                                    <label class="form-label">{{__('Dimensions')}}</label>
                                    <textarea type="text" class="form-control" name="dimensions" placeholder="90 x 60 cm"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{__('Medium')}}</label>
                                    <textarea type="text" class="form-control" name="medium"> </textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{__('Year')}}</label>
                                    <input type="number" class="form-control" name="year_created" min="1000"
                                        max="{{ date('Y') }}">
                                </div>
                            </div>

                            <!-- Pricing & Status -->
                            <div class="row mb-6">
                                <div class="col-md-4">
                                    <label class="form-label">{{__('Location')}}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="current_location">
                                    </div>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="mb-6">
                                <label class="form-label">{{__('Artwork Images*')}}</label>
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*"
                                    required>
                                <small class="text-muted">{{__('Or enter image URL:')}}</small>
                                <input type="url" class="form-control mt-2" name="image_url" placeholder="https://...">
                            </div>

                            <!-- Additional Information -->

                            <div class="mb-6">
                                <label class="form-label">{{__("Provenance")}}</label>
                                <textarea class="form-control" name="provenance" rows="2"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">{{__('Exhibition')}}</label>
                                <textarea class="form-control" name="exhibition" rows="2"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">{{__('Additional Comments')}}</label>
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">{{__('Source')}}</label>
                                <textarea class="form-control" name="source" rows="3"></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label">{{__('Status')}}</label>
                                <select name="status" class="select2 form-select" required>
                                    <option value="">{{__('Select option')}}</option>
                                    <option value="1">{{__("Completed")}}</option>
                                    <option value="2">{{__('Pending')}}</option>
                                    <option value="3">{{__('Doubtful')}}</option>
                                    <option value="4">{{__('Incomplete')}}</option>

                                </select>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">{{__('Save Artwork')}}</button>
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
                            <h5 class="card-title mb-0">{{__('Additional Information')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-repeater">
                                <div data-repeater-list="group-a">
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="mb-6 col-4">
                                                <label class="form-label">Options</label>
                                                <select name="additions" class="select2 form-select">
                                                    <option value="">{{__("Select option")}}</option>
                                                    <option value="height">{{__('height')}}</option>
                                                    <option value="framed">{{__('framed')}}</option>
                                                    <option value="weight">{{__('weight')}}</option>
                                                    <option value="width">{{__('width')}}</option>

                                                </select>
                                            </div>
                                            <div class="mb-6 col-8">
                                                <label class="form-label">{{__('Value')}}</label>
                                                <input type="text" name="additions_value" class="form-control"
                                                    placeholder="{{__('Enter value')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" data-repeater-create type="button">
                                    <i class='ti ti-plus ti-xs me-2'></i>
                                   {{__('Add another option')}}
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
