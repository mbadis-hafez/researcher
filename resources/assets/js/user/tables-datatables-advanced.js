/**
 * DataTables Advanced (jquery)
 */

'use strict';

$(function () {
  var
    dt_adv_filter_table = $('.dt-advanced-search');

  // Filter column wise function
  function filterColumn(i, val) {
    dt_adv_filter_table.DataTable().column(i).search(val, false, true).draw();
  }

  // Advance filter function
  // We pass the column location, the start date, and the end date
  $.fn.dataTableExt.afnFiltering.length = 0;


  // Advanced Filter table
  if (dt_adv_filter_table.length) {
    var dt_adv_filter = dt_adv_filter_table.DataTable({
      dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 dataTables_pager'p>>",
      ajax: this.baseURI,
      columns: [
        { data: '' },
        { data: 'artwork_title' },
        { data: 'dimensions' },
        { data: 'medium' },
        { data: 'source' },
        { data: 'artist_name' },
        { data: 'year' },
                { data: 'researcher_name' },

        { data: '' }
      ],

      columnDefs: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // Product name and artwork_description
          targets: 1,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['artwork_title'],
              $id = full['id'],
              $artwork_description = full['artwork_description'],
              $image = full['image'];

            if ($image) {
              // For Product image - wrapped in anchor tag with click handler
              var $output =
                '<a href="/users/artwork/view/' + $id + '" target="_blank">' + // Change the URL to match your route
                '<img src="' + $image +
                '" alt="Product-' + $id +
                '" class="rounded-2">' +
                '</a>';
            } else {
              // For Product badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['artwork_description'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-2 bg-label-' + $state + '">' + $initials + '</span>';
            }

            // Creates full output for Product name and artwork_description
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center product-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="custom-avatar avatar me-4 rounded-2 bg-label-secondary">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<h6 class="text-nowrap mb-0">' +
              $name +
              '</h6>' +
              '<small class="text-truncate d-none d-sm-block">' +
              $artwork_description +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          targets: -1, // Last column for favorite button
          orderable: false,
          className: 'text-center',
          render: function (data, type, full, meta) {
            return `
            <button class="btn btn-sm btn-icon favorite-btn ${full.is_favorited ? 'text-warning' : 'text-secondary'}" 
                    data-id="${full.id}" 
                    title="${full.is_favorited ? 'Remove from favorites' : 'Add to favorites'}">
                <i class="ti ti-star${full.is_favorited ? '-filled' : ''}"></i>
            </button>
        `;
          }
        }

      ],
      language: {
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      orderCellsTop: true,
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },
      initComplete: function () {
        var api = this.api();

        // Get the column index for "Artist Name"
        var artistNameColumnIndex = 5; // Adjust this index based on your table structure

        // Get unique artist names and sort them
        var artistNames = api
          .column(artistNameColumnIndex)
          .data()
          .unique()
          .sort();

        // Populate the select dropdown
        var select = $('select.dt-input[data-column="' + artistNameColumnIndex + '"]');
        artistNames.each(function (d) {
          select.append('<option value="' + d + '">' + d + '</option>');
        });

        // Re-initialize Select2 to reflect the new options
        select.trigger('change');
      }
    });

    // Handle favorite button clicks
    dt_adv_filter_table.on('click', '.favorite-btn', function () {
      var button = $(this);
      var artworkId = button.data('id');
      var isCurrentlyFavorited = button.hasClass('text-warning');

      // Show loading state
      button.prop('disabled', true);
      button.find('i').attr('class', 'ti ti-loader ti-spin');

      // Make AJAX call to your backend
      $.ajax({
        url: '/users/artworks/' + artworkId + '/favorite',
        method: isCurrentlyFavorited ? 'DELETE' : 'POST',
        dataType: 'json',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // For Laravel
        },
        success: function (response) {
          // Update button appearance
          if (response.success) {
            button.toggleClass('text-warning text-secondary');
            var iconClass = button.hasClass('text-warning') ? 'ti ti-star-filled' : 'ti ti-star';
            button.find('i').attr('class', iconClass);
            button.attr('title', button.hasClass('text-warning') ? 'Remove from favorites' : 'Add to favorites');

            // Optionally update the DataTable data
            var rowData = dt_adv_filter.row(button.closest('tr')).data();
            rowData.is_favorited = !isCurrentlyFavorited;
          }
        },
        error: function (xhr) {
          console.error('Error updating favorite:', xhr.responseText);
          // Show error message
          toastr.error('Failed to update favorite. Please try again.');
        },
        complete: function () {
          button.prop('disabled', false);
        }
      });
    });
  }

  $('select.dt-input').on('change', function () {
    var columnIndex = $(this).data('column');
    var selectedOptions = $(this).val(); // Array of selected values

    if (selectedOptions && selectedOptions.length > 0) {
      // Create a regex pattern to match any of the selected options
      var searchPattern = selectedOptions.join('|');
      dt_adv_filter.column(columnIndex).search(searchPattern, true, false).draw();
    } else {
      // If no options selected, clear the filter
      dt_adv_filter.column(columnIndex).search('').draw();
    }
  });



  // on key up from input field
  $('input.dt-input').on('keyup', function () {
    filterColumn($(this).attr('data-column'), $(this).val());
  });



  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 200);
});
