@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6">

                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{ url('/') }}" class="app-brand-link">
                                <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                                <span
                                    class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        @if (session('success'))
                            <!-- Success Message -->
                            <div class="alert alert-success mb-6">
                                {{ session('success') }}
                            </div>
                            <div class="text-center">
                                <p>We've received your application and will review it shortly.</p>
                                <a href="{{ url('/') }}" class="btn btn-primary">Return to Home</a>
                            </div>
                        @else
                            <!-- Registration Form -->
                            <h4 class="mb-1">Adventure starts here 🚀</h4>
                            <p class="mb-6">Make your app management easy and fun!</p>

                            <form id="formAuthentication" class="mb-6" action="{{ route('register') }}" method="POST">
                                @csrf
                                <!-- Your existing form fields here -->
                                <div class="mb-6">
                                    <label for="username" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter your username" autofocus>
                                </div>
                                
                                <div class="mb-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Enter your email">
                                </div>
                                <div class="mb-6">
                                    <label for="job" class="form-label">Job title/position</label>
                                    <input type="text" class="form-control" id="job" name="job"
                                        placeholder="Enter your job">
                                </div>

                                <div class="mb-6">
                                    <label for="phone" class="form-label">Phone number</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter your phone">
                                </div>

                                <div class="mb-6">
                                    <label for="business_type" class="form-label">Business Type</label>
                                    <select class="form-control" id="business_type" name="business_type" required>
                                        <option value="" disabled selected>Select your business type</option>
                                        <option value="hotel_hospitality">Hotel/Hospitality Group</option>
                                        <option value="architecture_firm">Architecture Firm</option>
                                        <option value="interior_design">Interior Design Studio</option>
                                        <option value="corporate_office">Corporate Office</option>
                                        <option value="restaurant_group">Restaurant Group</option>
                                        <option value="healthcare">Healthcare Facility</option>
                                        <option value="education">Educational Institution</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div id="other_business_type_container" class="mt-2" style="display: none;">
                                    <input type="text" class="form-control" id="other_business_type"
                                        name="other_business_type" placeholder="Please specify">
                                </div>

                                <div class="mb-6">
                                    <label for="organization" class="form-label">Organization</label>
                                    <input type="text" class="form-control" id="organization" name="organization"
                                        placeholder="Enter your organization name">
                                </div>
                                <script>
                                    document.getElementById('business_type').addEventListener('change', function() {
                                        const otherContainer = document.getElementById('other_business_type_container');
                                        otherContainer.style.display = this.value === 'other' ? 'block' : 'none';
                                    });
                                </script>
                                <div class="my-8">
                                    <div class="form-check mb-0 ms-2">
                                        <input class="form-check-input" type="checkbox" id="terms-conditions"
                                            name="terms">
                                        <label class="form-check-label" for="terms-conditions">
                                            I agree to
                                            <a href="javascript:void(0);">privacy policy & terms</a>
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn-primary d-grid w-100">
                                    Sign up
                                </button>
                            </form>

                            <p class="text-center">
                                <span>Already have an account?</span>
                                <a href="{{ url('auth/login') }}">
                                    <span>Sign in instead</span>
                                </a>
                            </p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
