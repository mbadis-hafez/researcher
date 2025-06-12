@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();
@endphp
@extends('layouts/commonMaster' )

@php
/* Display elements */
$contentNavbar = ($contentNavbar ?? true);
$containerNav = ($containerNav ?? 'container-xxl');
$isNavbar = ($isNavbar ?? true);
$isMenu = ($isMenu ?? true);
$isFlex = ($isFlex ?? false);
$isFooter = ($isFooter ?? true);
$customizerHidden = ($customizerHidden ?? '');

/* HTML Classes */
$navbarDetached = 'navbar-detached';
$menuFixed = (isset($configData['menuFixed']) ? $configData['menuFixed'] : '');
if(isset($navbarType)) {
  $configData['navbarType'] = $navbarType;
}
$navbarType = (isset($configData['navbarType']) ? $configData['navbarType'] : '');
$footerFixed = (isset($configData['footerFixed']) ? $configData['footerFixed'] : '');
$menuCollapsed = (isset($configData['menuCollapsed']) ? $configData['menuCollapsed'] : '');

/* Content classes */
$container = (isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';

@endphp

@section('layoutContent')
<div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
  <div class="layout-container">

    @if ($isMenu)
    @include('layouts/sections/menu/verticalMenu')
    @endif


    <!-- Layout page -->
    <div class="layout-page">

      {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want to use jetstream. --}}
      {{-- <x-banner /> --}}

      <!-- BEGIN: Navbar-->
      @if ($isNavbar)
      @include('layouts/sections/navbar/navbar')
      @endif
      <!-- END: Navbar-->


      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->
        @if ($isFlex)
          <div class="{{$container}} d-flex align-items-stretch flex-grow-1 p-0">
        @else
          <div class="{{$container}} flex-grow-1 container-p-y">
        @endif

          @yield('content')

          </div>
          <!-- / Content -->

          <!-- Footer -->
          @if ($isFooter)
          @include('layouts/sections/footer/footer')
          @endif
          <!-- / Footer -->
          <div class="content-backdrop fade"></div>
        </div>
        <!--/ Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    @if ($isMenu)
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    @endif
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
  @endsection
  @section('scripts')
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
    <script>
      /** SweetAlert Confirmation Popup **/
      document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.delete-confirmation');
        forms.forEach(form => {
          form.addEventListener('submit', function(event) {
            event.preventDefault();
            const title = form.dataset.title ? form.dataset.title :
              "{{ trans('Are you sure?') }}";
            const text = form.dataset.text ? form.dataset.text :
              "{{ trans('This action is irreversible. Once deleted, you will not be able to recover this record!') }}";

            Swal.fire({
              title: title,
              text: text,
              animation: false,
              confirmButtonText: "{{ trans('Yes, delete') }}",
              cancelButtonText: "{{ trans('Cancel') }}",
              icon: 'warning',
              showCancelButton: true,
              customClass: {
                confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
              },
              buttonsStyling: false
            }).then(function(result) {
              if (result.isConfirmed) {
                form.submit();
              } else {
                return false;
              }
            });
          });
        });
      });
      /** End of SweetAlert Confirmation Popup **/
    </script>
  @endsection
