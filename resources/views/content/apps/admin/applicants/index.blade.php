@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js'])
@endsection

@section('page-script')
    @vite('resources/assets/js/admin/app-applicants-list.js')
@endsection

@section('content')
    <!-- Users List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Job Title</th>
                        <th>Business Type</th>
                        <th>Email verification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header border-bottom">
                <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
                <form class="add-new-user pt-0" id="addNewUserForm" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-6">
                        <label class="form-label" for="add-user-fullname">Full Name</label>
                        <input type="text" class="form-control" id="add-user-fullname" required placeholder="John Doe"
                            name="userFullname" aria-label="John Doe" />
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="add-user-email">Email</label>
                        <input type="text" id="add-user-email" class="form-control" required
                            placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="userEmail" />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-contact">Contact</label>
                        <input type="text" id="add-user-contact" class="form-control phone-mask"
                            placeholder="+1 (609) 988-44-11" aria-label="john.doe@example.com" name="userContact" />
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="add-user-organization">Organization</label>
                        <input type="text" id="add-user-organization" class="form-control" placeholder="Hafez Gallery"
                            aria-label="jdoe1" name="organization" />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-job_title">Job Title</label>
                        <input type="text" id="add-user-job_title" class="form-control" placeholder="Hafez Gallery"
                            aria-label="jdoe1" name="job_title" />
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

                    <!-- Specific Business Type Field (Initially Hidden) -->
                    <div class="mb-6" id="specific-business-type-container" style="display: none;">
                        <label class="form-label" for="specific-business-type">Specific Business Type</label>
                        <input type="text" id="specific-business-type" class="form-control"
                            placeholder="Please specify your business type" name="specific_business_type" />
                    </div>

                    <!-- Add this checkbox below your business type fields -->
                    <div class="mb-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved"
                                value="1" checked>
                            <label class="form-check-label" for="is_approved">Approved</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-3 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Business type change handler
            document.getElementById('business_type').addEventListener('change', function() {
                const specificContainer = document.getElementById('specific-business-type-container');
                if (this.value === 'other') {
                    specificContainer.style.display = 'block';
                    document.getElementById('specific-business-type').required = true;
                } else {
                    specificContainer.style.display = 'none';
                    document.getElementById('specific-business-type').required = false;
                }
            });

            // Edit record handler
            $(document).on('click', '.edit-record', function() {
                const userId = $(this).data('id');

                // Fetch user data
                fetch(`${baseUrl}admin/applicants/${userId}/edit`)
                    .then(response => response.json())
                    .then(user => {
                        // Populate form fields
                        $('#add-user-fullname').val(user.full_name);
                        $('#add-user-email').val(user.email);
                        $('#add-user-contact').val(user.phone);
                        $('#add-user-organization').val(user.organization);
                        $('#add-user-job_title').val(user.job_title);

                        // Set business type and trigger change
                        $('#business_type').val(user.business_type).trigger('change');

                        // If business type is 'other', show and populate the specific field
                        if (user.business_type === 'other') {
                            $('#specific-business-type-container').show();
                            $('#specific-business-type').val(user.specific_business_type || '');
                        }

                        // Update form action
                        $('#addNewUserForm').attr('action', `${baseUrl}admin/applicants/${userId}`);
                        $('#addNewUserForm').append('<input type="hidden" name="_method" value="PUT">');

                        // Change modal title
                        $('.offcanvas-title').text('Edit User');

                        // Open the modal
                        new bootstrap.Offcanvas(document.getElementById('offcanvasAddUser')).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Failed to load user data');
                    });
            });

            // Reset form when modal is hidden
            document.getElementById('offcanvasAddUser').addEventListener('hidden.bs.offcanvas', function() {
                $('#addNewUserForm')[0].reset();
                $('#addNewUserForm').attr('action', `${baseUrl}admin/users`);
                $('#addNewUserForm').find('input[name="_method"]').remove();
                $('.offcanvas-title').text('Add New User');
                $('#specific-business-type-container').hide();
            });
        });
    </script>
@endsection
