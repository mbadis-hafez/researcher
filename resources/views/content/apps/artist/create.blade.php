@extends('layouts/layoutMaster')

@section('title', 'Create Artist')
@section('page-style')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Flag Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css" />
    {{-- dropzone CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/dropzone/dropzone.min.scss') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/dropzone/dropzone.min.css') }}" type="text/css" />
    <style>
        .dropzone {
            border: 2px dashed lightgray;
        }
    </style>
@endsection
<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
        'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
        'resources/assets/vendor/libs/select2/select2.scss',
        // 'resources/assets/vendor/libs/dropzone/dropzone.scss'
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
        'resources/assets/vendor/libs/cleavejs/cleave.js',
        'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
        'resources/assets/vendor/libs/moment/moment.js',
        'resources/assets/vendor/libs/flatpickr/flatpickr.js',
        // 'resources/assets/vendor/libs/select2/select2.js',
        // 'resources/assets/vendor/libs/dropzone/dropzone.js'
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite([
        'resources/assets/js/form-layouts.js',
        // 'resources/assets/js/artist-images-upload.js'
    ])
@endsection

@section('content')

    <!-- Form with Tabs -->
    <div class="row">
        <div class="col">
            <div class="card mb-6">
                <div class="card-header px-0 pt-0">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#form-tabs-details" aria-controls="form-tabs-details" role="tab"
                                    aria-selected="true"><span class="ti ti-user ti-lg d-sm-none"></span><span
                                        class="d-none d-sm-block">{{ trans('Details') }} </span></button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <div class="tab-content p-0">
                        <form action="{{ route('app-artist-store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-pane fade active show" id="form-tabs-details" role="tabpanel">
                               
                                <div class="row g-6">
                                    <div class="col-md-6">
                                        <label class="form-label" for="formtabs-name">{{ trans('Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" required id="formtabs-name" class="form-control"
                                            name="name" placeholder="John" />
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
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- dropzone JS --}}
    <script src="{{ asset('assets/dropzone/dropzone.min.js') }}"></script>
    <script>
        // Update/reset user image of account page
        let accountUserImage = document.getElementById('uploadedAvatar');
        const fileInput = document.querySelector('.account-file-input'),
            resetFileInput = document.querySelector('.account-image-reset');

        if (accountUserImage) {
            const resetImage = accountUserImage.src;
            fileInput.onchange = () => {
                if (fileInput.files[0]) {
                    accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            };
            resetFileInput.onclick = () => {
                fileInput.value = '';
                accountUserImage.src = resetImage;
            };
        }
    </script>
    <script>
        /** Country flag **/
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
        /** End of Country flag **/

        /** Dropzone multiple uplaod images **/
        // ? Start your code from here
        var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
        dropzonePreviewNode.id = "";
        var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
        dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
        var dropzone = new Dropzone(".dropzone-images", {
            url: '/app/artist/upload-additional-images',
            method: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            previewTemplate: previewTemplate,
            previewsContainer: "#dropzone-preview",
            parallelUploads: 1,
            maxFilesize: 5,
            addRemoveLinks: true,
            success: function(file, response) {
                // Save filename and append to form as hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'images[]';
                input.value = response.fileName;
                document.getElementById('uploaded-images').appendChild(input);
                if (response.error) {
                    file.previewTemplate.remove();
                }
            },
            removedfile: function(file) {
                const fileName = file.upload.filename;
                file.previewElement.remove();

                // Remove the hidden input
                const inputs = document.querySelectorAll(`input[value="${fileName}"]`);
                inputs.forEach(input => input.remove());

                // Optionally make request to delete from temp storage
            }
        });
        /** End Dropzone multiple uplaod images **/
    </script>
@endsection
