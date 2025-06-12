@extends('layouts/layoutMaster')

@section('title', 'DataTables - Advanced Tables')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/user/tables-datatables-advanced.js'])
@endsection

@section('content')


    <style>
        .custom-avatar {
            width: 100px;
            height: auto;
        }
    </style>

    <!-- Advanced Search -->
    <div class="card">
        <h5 class="card-header">{{ __('Advanced Search') }}</h5>
        <!--Search Form -->
        <div class="card-body">
            <form class="dt_adv_search" method="POST">
                <div class="row">
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">{{ __('Title') }}</label>
                                <input type="text" class="form-control dt-input dt-full-name" data-column=1
                                    placeholder="Alaric Beslier" data-column-index="0">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">{{ __('Dimension') }}</label>
                                <input type="text" class="form-control dt-input" data-column=2 placeholder="Dimension"
                                    data-column-index="1">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">{{ __('Medium') }}</label>
                                <input type="text" class="form-control dt-input" data-column=3 placeholder="Medium"
                                    data-column-index="2">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">{{ __('Source') }}</label>
                                <input type="text" class="form-control dt-input" data-column=4 placeholder="Source"
                                    data-column-index="3">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">{{ __('Artist Name') }}</label>
                                <select class="form-select dt-input select2-multiple" data-column="5" multiple="multiple"
                                    style="width: 100%;">
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label">{{ __('Year') }}</label>
                                <input type="text" class="form-control dt-input" data-column=6 placeholder="2025"
                                    data-column-index="5">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-advanced-search table">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Dimension') }}</th>
                        <th>{{ __('Medium') }}</th>
                        <th>{{ __('Source') }}</th>
                        <th>{{ __('Artist Name') }}</th>
                        <th>{{ __('Year') }}</th>
                        <th>{{ __('Researcher Name') }}</th>

                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Dimension') }}</th>
                        <th>{{ __('Medium') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Artist Name') }}</th>
                        <th>{{ __('Year') }}</th>
                        <th>{{ __('Researcher Name') }}</th>

                        <th>{{ __('Action') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!--/ Advanced Search -->


@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Select Artist(s)",
                allowClear: true,
                closeOnSelect: false,
                width: 'resolve' // Adjusts to the width of the container
            });
        });
    </script>
@endsection
