@extends('layouts/layoutMaster')

@section('title', 'Create Contact')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/dropzone/dropzone.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/form-layouts.js', 'resources/assets/js/contact-images-upload.js'])
@endsection

@section('content')

    <!-- Form with Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h3 class="display-5">{{ trans('instructions') }} : </h3>
                <p>{{ trans('1') }}. {{ trans('download the format file and fill it with proper data.') }}</p>

                <p>{{ trans('2') }}.
                    {{ trans('you can download the example file to understand how the data must be filled.') }}</p>

                <p>{{ trans('3') }}. {{ trans('once you have downloaded and filled the format file') }},
                    {{ trans('upload it in the form below and submit.') }}</p>

                <p>{{ trans('4') }}. {{ trans('the country code must be 2 letters uppercase') }}</p>

            </div>
        </div>
        <div class="col-md-12 mt-2">
            <form action="{{ route('app-contact-store-by-bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card rest-part">
                    <div class="px-3 py-4 d-flex flex-wrap align-items-center gap-10 justify-content-center">
                        <h4 class="mb-0">{{ trans('do not have the template') }} ?</h4>
                        <a href="{{ asset('public/assets/template/contact_bulk_import_template.csv') }}" download=""
                            class="btn-link text-capitalize fz-16 font-weight-medium">{{ trans('download here') }}</a>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <div class="uploadDnD">
                                        <div class="form-group inputDnD input_image input_image_edit"
                                            data-title="{{ trans('drag & drop file or browse file') }}">
                                            <input type="file" name="contacts_file" accept=".xlsx, .xls"
                                                class="form-control-file text--primary font-weight-bold action-upload-section-dot-area"
                                                id="inputFile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-10 align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary me-4">{{ trans('Submit') }} </button>
                            <button type="reset" class="btn btn-label-secondary">{{ trans('Cancel') }} </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
