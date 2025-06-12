@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutFront')

@section('title', 'Email Verified - Pages')

@section('page-style')
<!-- Page -->
@vite('resources/assets/vendor/scss/pages/page-auth.scss')
@endsection

@section('content')
<div class="authentication-wrapper authentication-basic px-6">
  <div class="authentication-inner py-6">
    <!-- Verify Email -->
    <div class="card">
      <div class="card-body">
        <!-- Logo -->
        <div class="app-brand justify-content-center mb-6">
          <a href="{{url('/')}}" class="app-brand-link">
            <span class="app-brand-logo demo">@include('_partials.macros',['height'=>20,'withbg' => "fill: #fff;"])</span>
            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
          </a>
        </div>
        <!-- /Logo -->
        <h4 class="mb-1">Email Verified Successfully! ✅</h4>
        <p class="text-start mb-0">
          Your email address has been successfully verified. Thank you for completing this important step.
        </p>
        <p class="text-start mb-6">
          You can now access all features of your account.
        </p>
        <a class="btn btn-primary w-100 my-6" href="{{url('/')}}">
          Continue to Dashboard
        </a>
      </div>
    </div>
    <!-- /Verify Email -->
  </div>
</div>
@endsection