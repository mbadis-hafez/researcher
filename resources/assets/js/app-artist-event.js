/**
 * Page artist event
 */

'use strict';

document.addEventListener('DOMContentLoaded', function() {
  // Delete confirmation
  $(document).on('submit', '.delete-confirmation', function(e) {
      e.preventDefault();
      const form = this;
      
      Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              form.submit();
          }
      });
  });
});

