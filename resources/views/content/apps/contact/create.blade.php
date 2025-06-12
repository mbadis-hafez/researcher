@extends('layouts/layoutMaster')

@section('title', 'Create Contact')
@section('page-style')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Flag Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css" />
@endsection
<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
        'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
        'resources/assets/vendor/libs/select2/select2.scss',
        'resources/assets/vendor/libs/dropzone/dropzone.scss'
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
        'resources/assets/vendor/libs/dropzone/dropzone.js'
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite([
        'resources/assets/js/form-layouts.js',
    ])
@endsection

@section('content')

    <!-- Form with Tabs -->
    <div class="row">
        <div class="col">
            <div class="card mb-6">
                <div class="card-body">
                    <form action="{{ route('app-contact-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-6">
                            <h3 class="text-uppercase">{{ trans('Contact') }}</h3>
                            <div class="d-flex align-items-start align-items-sm-center gap-6">
                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">{{ trans('Upload new photo') }}</span>
                                        <i class="ti ti-upload d-block d-sm-none"></i>
                                        <input type="file" name='image' id="upload"
                                            class="account-file-input" hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                        <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">{{ trans('Reset') }} </span>
                                    </button>
                                    <div>{{ trans('Allowed JPG, GIF or PNG. Max size of 800K') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="switch switch-md">
                                    <span class="switch-label">{{ trans('Organization record') }}</span>
                                    <input type="checkbox" class="switch-input" name="organisation_record" />
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                      </span>
                                      <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                      </span>
                                    </span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-first-name">{{ trans('First Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" required id="formtabs-first-name" class="form-control"
                                    name="first_name" placeholder="John" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-last-name">{{ trans('Last Name') }} <span
                                    class="text-danger">*</span></label>
                                <input type="text" required name="last_name" id="formtabs-last-name" class="form-control"
                                    placeholder="Doe" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-last-name">{{ trans('Position') }} </label>
                                <input type="text" name="position" id="formtabs-last-name"
                                    class="form-control" />
                            </div>
                            <div class="col-md-12 d-none" id="organisation">
                                <label class="form-label" for="formtabs-organisation">{{ trans('Organisation') }}
                                </label>
                                <input type="text" name="organisation" id="formtabs-organisation" class="form-control">
                            </div>
                            <hr>
                            <h3 class="text-uppercase">{{ trans('Email') }}</h3>
                            <div class="col-md-12">
                                <label class="form-label" for="formtabs-email">{{ trans('Email') }} {{ trans('(for mailings)') }}
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="email" name="email" id="formtabs-email" class="form-control" placeholder="{{ trans('Main') }}">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="" id="formtabs-email" class="form-control" >
                                    </div>
                                    <span class="mt-2">{{ trans('(you may add multiple email addresses, comma-seperated)') }}</span>
                                </div>
                            </div>
                            <hr>
                            <h3 class="text-uppercase">{{ trans('Telephone') }}</h3>
                            <div class="col-md-12">
                                <label class="form-label" for="formtabs-phone_number">{{ trans('Telephone') }}
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="phone_number" id="formtabs-phone_number" class="form-control" placeholder="{{ trans('Main') }}">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="tel" name="phone_number" id="formtabs-email" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="formtabs-addional_phone_number">{{ trans('Additionnal telephones') }}
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="addional_phone_number" id="formtabs-addional_phone_number" class="form-control" placeholder="{{ trans('labels') }}">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="tel" name="addional_phone_number" id="formtabs-addional_phone_number" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h3 class="text-uppercase">{{ trans('Internet') }}</h3>
                            <div class="col-md-12">
                                <label class="form-label" for="formtabs-website">{{ trans('Website') }}
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="website" id="formtabs-website" class="form-control" placeholder="{{ trans('Main') }}">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="" placeholder="https://" id="formtabs-email" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="formtabs-addional_website">{{ trans('Additionnal websites') }}
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="addional_website" id="formtabs-addional_website" class="form-control" placeholder="{{ trans('labels') }}">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="" id="formtabs-addional_website" class="form-control" placeholder="https://" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="formtabs-social">{{ trans('Social') }}
                                </label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                                            <input type="text" name="social" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon-search31" />
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="" id="formtabs-social" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h3 class="text-uppercase">{{ trans('Addresses') }}</h3>
                            <div class="col-md-6">
                                <button class="btn btn-outline-primary show-address-fields" type="button">
                                    <i class="fas fa-plus"></i>
                                    {{ trans('New Address') }}
                                </button>
                            </div>
                            <div class="row mt-4 d-none" id="address-fields">
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-country">{{ trans('Country') }} </label>
                                    <select id="multicol-country" name="country" class="select2 form-select"
                                        data-allow-clear="true">
                                        <option value="">{{ trans('Select') }}</option>
                                        @foreach ($countries as $key => $country)
                                            @php
                                                $country = country($key);
                                            @endphp
                                            <option value="{{ $country->getIsoAlpha2() }}"
                                                data-flag="{{ strtolower($country->getIsoAlpha2()) }}">
                                                {{ $country->getName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="formtabs-city">{{ trans('City') }} </label>
                                    <input type="text" required name="city" id="formtabs-city" class="form-control"
                                     />
                                </div>
                                <div class="col-md-6 mt-4">
                                    <label class="form-label" for="formtabs-street">{{ trans('Street') }} </label>
                                    <input type="text" required id="formtabs-street" class="form-control"
                                        name="street" />
                                </div>
                                <div class="col-md-6 mt-4">
                                    <label class="form-label" for="formtabs-zip-code">{{ trans('Zip code') }} </label>
                                    <input type="text" required name="zip_code" id="formtabs-zip-code" class="form-control"
                                    />
                                </div>
                            </div>
                            <hr>
                            <h3 class="text-uppercase">{{ trans('General') }}</h3>
                            <div class="col-md-12">
                                <small class="text-light fw-medium">{{ trans('Gender') }}</small>
                                <div class="form-check mt-4">
                                  <input name="default-radio-1" class="form-check-input" type="radio" value="male" id="male" />
                                  <label class="form-check-label" for="male">
                                    {{ trans('male') }}
                                  </label>
                                </div>
                                <div class="form-check mt-4">
                                    <input name="default-radio-1" class="form-check-input" type="radio" value="female" id="female" />
                                    <label class="form-check-label" for="female">
                                      {{ trans('female') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="default-radio-1" class="form-check-input" type="radio" value="non binary" id="non-binary" />
                                    <label class="form-check-label" for="non-binary">
                                      {{ trans('non binary') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="default-radio-1" class="form-check-input" type="radio" value="prefer not to say" id="prefer-not-to-say" />
                                    <label class="form-check-label" for="prefer-not-to-say">
                                      {{ trans('prefer not to say') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="default-radio-1" class="form-check-input" type="radio" value="N/A" id="N/A" />
                                    <label class="form-check-label" for="N/A">
                                      {{ trans('N/A') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="default-radio-1" class="form-check-input" type="radio" value="Other" id="Other" />
                                    <label class="form-check-label" for="Other">
                                      {{ trans('Other') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-spoken_languages">{{ trans('Spoken languages') }} </label>
                                <select id="spoken_languages" name="spoken_languages[]" class="select2 form-select" multiple
                                        data-allow-clear="true">
                                    <option value="">{{ trans('Select') }}</option>
                                    <option value="Arabic">{{ trans('Arabic') }}</option>
                                    <option value="French">{{ trans('French') }}</option>
                                    <option value="English">{{ trans('English') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="formtabs-date_of_birth">{{ trans('Date Of Birth') }} </label>
                                <input type="text" name="date_of_birth" id="formtabs-date_of_birth" placeholder="YYYY-MM-DD"
                                    class="form-control dob-picker" />
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="btn btn-primary me-4">{{ trans('Submit') }} </button>
                            <button type="reset" class="btn btn-label-secondary">{{ trans('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        /** Country flag **/
        function formatCountry(option) {
            if (!option.id) return option.text;
            const flag = $(option.element).data('flag');
            const countryName = option.text;
            return $(`<span><span class="fi fi-${flag} me-2"></span> ${countryName}</span>`);
        }

        $('#multicol-country').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
        });
        /** End of Country flag **/
        $('#spoken_languages').select2({
        });
    
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
        $('.show-address-fields').click(function(){
            $('#address-fields').removeClass('d-none');
        })
        $('.switch-input').change(function () {
            if ($(this).is(':checked')) {
                $('#organisation').removeClass('d-none');
            } else {
                $('#organisation').addClass('d-none')
            }
        });
</script>
@endsection