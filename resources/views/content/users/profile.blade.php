@extends('layouts/layoutMaster')

@section('title', 'Account settings - Account')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                class="ti-sm ti ti-users me-1_5"></i> Account</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.users.security', ['user' => auth()->id()]) }}">
                            <i class="ti-sm ti ti-lock me-1_5"></i> Security
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body pt-4">
                    <form id="formAccountSettings" method="POST" action="{{ route('users.users.update',['user' => auth()->id()]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="firstName" class="form-label">Full Name</label>
                                <input class="form-control" type="text" id="full_name" name="name"
                                    value="{{ old('name', $user->name) }}" autofocus />
                                @error('full_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" placeholder="john.doe@example.com" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4 col-md-6">
                                <label for="organization" class="form-label">Organization</label>
                                <input type="text" class="form-control" id="organization" name="organization"
                                    value="{{ old('organization', $user->organization) }}" />
                                @error('organization')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="phoneNumber">Phone Number</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">US (+1)</span>
                                    <input type="text" id="phone" name="phone"
                                        value="{{ old('phone', $user->phone) }}" class="form-control"
                                        placeholder="202 555 0111" />
                                </div>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4 col-md-6">
                                <label for="job_title" class="form-label">Job Title</label>
                                <input type="text" class="form-control" id="job_title" name="job_title"
                                    value="{{ old('job_title', $user->job_title) }}" placeholder="Job title" />
                                @error('job_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4 col-md-6">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-control" id="business_type" name="business_type" required>
                                    <option value="" disabled
                                        {{ old('business_type', $user->business_type) ? '' : 'selected' }}>Select your
                                        business type</option>
                                    <option value="hotel_hospitality"
                                        {{ old('business_type', $user->business_type) == 'hotel_hospitality' ? 'selected' : '' }}>
                                        Hotel/Hospitality Group</option>
                                    <option value="architecture_firm"
                                        {{ old('business_type', $user->business_type) == 'architecture_firm' ? 'selected' : '' }}>
                                        Architecture Firm</option>
                                    <option value="interior_design"
                                        {{ old('business_type', $user->business_type) == 'interior_design' ? 'selected' : '' }}>
                                        Interior Design Studio</option>
                                    <option value="corporate_office"
                                        {{ old('business_type', $user->business_type) == 'corporate_office' ? 'selected' : '' }}>
                                        Corporate Office</option>
                                    <option value="restaurant_group"
                                        {{ old('business_type', $user->business_type) == 'restaurant_group' ? 'selected' : '' }}>
                                        Restaurant Group</option>
                                    <option value="healthcare"
                                        {{ old('business_type', $user->business_type) == 'healthcare' ? 'selected' : '' }}>
                                        Healthcare Facility</option>
                                    <option value="education"
                                        {{ old('business_type', $user->business_type) == 'education' ? 'selected' : '' }}>
                                        Educational Institution</option>
                                    <option value="other"
                                        {{ old('business_type', $user->business_type) == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('business_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="other_business_type_container" class="mb-4 col-md-12"
                                style="display: {{ old('business_type', $user->business_type) == 'other' ? 'block' : 'none' }};">
                                <label for="other_business_type" class="form-label">Specify Business Type</label>
                                <input type="text" class="form-control" id="other_business_type"
                                    name="other_business_type"
                                    value="{{ old('other_business_type', $user->business_type == 'other' ? $user->business_type : '') }}"
                                    placeholder="Please specify">
                                @error('other_business_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <script>
                                document.getElementById('business_type').addEventListener('change', function() {
                                    const otherContainer = document.getElementById('other_business_type_container');
                                    otherContainer.style.display = this.value === 'other' ? 'block' : 'none';

                                    // Make the field required/not required based on selection
                                    document.getElementById('other_business_type').required = this.value === 'other';
                                });
                            </script>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                    </form>
                </div>
                <!-- /Account -->
            </div>
            <div class="card">
                <h5 class="card-header">Delete Account</h5>
                <div class="card-body">
                    <div class="mb-6 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check my-8">
                            <input class="form-check-input" type="checkbox" name="accountActivation"
                                id="accountActivation" />
                            <label class="form-check-label" for="accountActivation">I confirm my account
                                deactivation</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account" disabled>Deactivate
                            Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
