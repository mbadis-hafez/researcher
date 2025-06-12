@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Account settings - Security')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-account-settings.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/pages-account-settings-security.js', 'resources/assets/js/modal-enable-otp.js'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('users/users/profile') }}"><i
                                class="ti-sm ti ti-users me-1_5"></i> Account</a></li>
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                class="ti-sm ti ti-lock me-1_5"></i> Security</a></li>
                </ul>
            </div>
            <!-- Change Password -->
            <div class="card mb-6">
                <h5 class="card-header">Change Password</h5>
                <div class="card-body pt-1">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="currentPassword">Current Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="currentPassword" id="currentPassword"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('currentPassword')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="newPassword">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="newPassword" name="newPassword"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('newPassword')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="confirmPassword" id="confirmPassword"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('confirmPassword')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <h6 class="text-body">Password Requirements:</h6>
                        <ul class="ps-4 mb-0">
                            <li class="mb-4">Minimum 8 characters long - the more, the better</li>
                            <li class="mb-4">At least one lowercase character</li>
                            <li>At least one number, symbol, or whitespace character</li>
                        </ul>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
            <!--/ Change Password -->


            <!-- Recent Devices -->
            <div class="card mb-6">
                <h5 class="card-header">Recent Devices</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Browser</th>
                                <th class="text-truncate">Device</th>
                                <th class="text-truncate">Location</th>
                                <th class="text-truncate">Recent Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                            @endforeach
                            <tr>
                                <td class="text-truncate text-heading fw-medium">
                                    <i class='ti {!! Helper::getBrowserIcon($activity->browser) !!} ti-md align-top text-info me-2'></i>
                                    {{ $activity->browser }}
                                </td>
                                <td class="text-truncate">{{ $activity->device }}</td>
                                <td class="text-truncate">{{ $activity->country }}</td>
                                <td class="text-truncate">{{ $activity->created_at }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!--/ Recent Devices -->

        </div>
    </div>

@endsection
<script>
document.querySelectorAll('.form-password-toggle .input-group-text').forEach(function(eyeIcon) {
    eyeIcon.addEventListener('click', function() {
        const passwordInput = this.previousElementSibling;
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('ti-eye-off', 'ti-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('ti-eye', 'ti-eye-off');
        }
    });
});
</script>