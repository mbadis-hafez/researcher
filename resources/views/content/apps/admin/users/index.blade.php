@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js'])
@endsection

@section('page-script')
    @vite('resources/assets/js/admin/app-user-list.js')
@endsection

@section('content')

   
    <!-- Users List Table -->
    <div class="card">
       
        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th>ID</th>
                        <th>ID</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
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

                    <!-- Existing fields... -->

                    <div class="mb-6">
                        <label class="form-label" for="add-user-password">Password</label>
                        <div class="input-group">
                            <input type="text" id="add-user-password" class="form-control" name="password"
                                placeholder="Generate or enter password" required>
                            <button type="button" class="btn btn-outline-primary" id="generate-password">
                                <i class="ti ti-refresh me-1"></i> Generate
                            </button>
                        </div>
                        <small class="text-muted">Password must be at least 8 characters</small>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="add-user-contact">Contact</label>
                        <input type="text" id="add-user-contact" class="form-control phone-mask"
                            placeholder="+1 (609) 988-44-11" aria-label="john.doe@example.com" name="userContact" />
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="add-user-company">Company</label>
                        <input type="text" id="add-user-company" class="form-control" placeholder="Hafez Gallery"
                            aria-label="jdoe1" name="companyName" />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="user-role">User Role</label>
                        <select id="user-role" name="user-role" class="form-select" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
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
            // Password generation function
            function generatePassword(length = 12) {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
                let password = '';
                for (let i = 0; i < length; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return password;
            }

            // Generate password button click handler
            document.getElementById('generate-password').addEventListener('click', function() {
                document.getElementById('add-user-password').value = generatePassword();
            });
        });


        // Add this after your DataTable initialization
        $(document).on('click', '.edit-record', function() {
            const userId = $(this).data('id');

            // Fetch user data
            fetch(`${baseUrl}admin/users/${userId}/edit`)
                .then(response => response.json())
                .then(user => {
                    // Populate form fields
                    $('#add-user-fullname').val(user.full_name);
                    $('#add-user-email').val(user.email);
                    $('#add-user-contact').val(user.phone);
                    $('#add-user-company').val(user.company);
                    $('#add-user-password').val('').attr('placeholder', 'Leave blank to keep current password');
                    if (user.roles && user.roles.length > 0) {
                        $('#user-role').val(user.roles[0].name).trigger('change');
                    }
                    $('#user-type').val(user.type).trigger('change');

                    // Update form action
                    $('#addNewUserForm').attr('action', `${baseUrl}admin/users/${userId}`);
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
            $('#add-user-password').attr('placeholder', 'Generate or enter password');
        });
    </script>
@endsection
