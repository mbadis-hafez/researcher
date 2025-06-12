@extends('layouts/layoutMaster')

@section('title', 'Artist - Artworks')

<!-- Page -->
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-profile.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection


@section('page-script')
    @vite(['resources/assets/js/admin/artworks_list.js'])
@endsection



@section('content')
   

 
    <!-- Teams Cards -->
    <div class="row">
    

        <!-- Product List Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{__('Filter')}}</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-6 gap-md-0">
                    <div class="col-md-4 product_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-products table">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{__('Title')}}</th>
                            <th>{{__('Medium')}}</th>
                            <th>{{__('Dimensions')}}</th>
                            <th>{{__('Year')}}</th>
                            <th>{{__('Created By')}}</th>
                            <th>{{__('Researcher Name')}}</th>
                            <th>{{__('status')}}</th>
                            <th>{{__('actions')}}</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
@endsection


@section('scripts')

@endsection
