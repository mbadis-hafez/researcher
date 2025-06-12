/**
 * app-ecommerce-product-list
 */

'use strict';

// Datatable (jquery)
$(function () {
  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table
  var dt_product_table = $('.datatables-products'),
    productAdd = baseUrl + 'artist/artwork/add',
    statusObj = {
      1: { title: 'Not available', class: 'bg-label-secondary' },
      2: { title: 'Consigned / For sale', class: 'bg-label-success' },
      3: { title: 'Sold', class: 'bg-label-danger' },
      4: { title: 'Not for sale', class: 'bg-label-warning' },
      5: { title: 'Details Pending', class: 'bg-label-info' }
    },

    categoryObj = {
      0: { title: '' },
      1: { title: 'Painting' },
      2: { title: 'Drawing, Collage Or Other Work On Paper' },
      3: { title: 'Photography/ تصوير فوتوغرافي' },
      4: { title: 'Digital Art' },
      5: { title: 'Print' },
      6: { title: 'Sculptures' },
      7: { title: 'Artifacts' },
      8: { title: 'Books' },
      9: { title: 'Ceramics & Glass' },
      10: { title: 'Fiber works/tapestry' },
      11: { title: 'Fossils/minerals' },
      12: { title: 'Furniture' },
      13: { title: 'Gouache' },
      14: { title: 'Jewellery' },
      15: { title: 'Judaica' },
      16: { title: 'Manuscripts' },
      17: { title: 'Mascots' },
      18: { title: 'Rugs' },
      19: { title: 'Textiles' },
      20: { title: 'Wood' },
      21: { title: 'Unclassified' },

    };

  // E-commerce Products datatable

  if (dt_product_table.length) {
    var dt_products = dt_product_table.DataTable({
      ajax: baseUrl + 'artist/artwork/data', // JSON file to add data
      columns: [
        // columns according to JSON
        { data: 'id' },
        { data: 'id' },
        { data: 'artwork_title' },
        { data: 'category' },
        { data: 'medium' },
        { data: 'dimensions' },
        { data: 'year' },
        { data: 'price' },
        { data: 'status' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // Product name and artwork_description
          targets: 2,
          responsivePriority: 1,
          render: function (data, type, full, meta) {
            var $name = full['artwork_title'],
              $id = full['id'],
              $artwork_description = full['artwork_description'],
              $image = full['image'];
            if ($image) {
              // For Product image

              var $output =
                '<img src="' +
                $image +
                '" alt="Product-' +
                $id +
                '" class="rounded-2">';
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
              '<div class="avatar avatar me-4 rounded-2 bg-label-secondary">' +
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
          // Product Category

          targets: 3,
          responsivePriority: 5,
          render: function (data, type, full, meta) {
            var $category = categoryObj[full['category']].title;
           
            return (
              "<span class='text-truncate d-flex align-items-center text-heading'>" +
              $category +
              '</span>'
            );
          }
        },
        {
          target:4,
          render: function (data, type, full, meta) {
            var $medium = full['medium'];

            return '<span>' + $medium + '</span>';
          }
        },
        {
          // dimension
          targets: 5,
          render: function (data, type, full, meta) {
            var $dimension = full['dimensions'];

            return '<span>' + $dimension + '</span>';
          }
        },
        {
          // year
          targets: 6,
          render: function (data, type, full, meta) {
            var $year = full['year'];

            return '<span>' + $year + '</span>';
          }
        },
        {
          // price
          targets: 7,
          render: function (data, type, full, meta) {
            var $price = full['price'];

            return '<span>' + $price + '</span>';
          }
        },
        {
          // Status
          targets: 8,
          render: function (data, type, full, meta) {
            var $status = full['status'];

            return (
              '<span class="badge ' +
              statusObj[$status].class +
              '" text-capitalized>' +
              statusObj[$status].title +
              '</span>'
            );
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            // Assuming 'full' contains your row data with an 'id' property
            // Modify these URLs according to your needs
            const editUrl = `/artist/artwork/edit/${full.id}`;
            const viewUrl = `/artist/artwork/view/${full.id}`;
            const suspendUrl = `/artist/artwork/suspend/${full.id}`;
            
            return (
              '<div class="d-flex align-items-sm-center justify-content-sm-center">' +
              `<a href="${editUrl}" class="btn btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-edit"></i></a>` +
              '<button class="btn btn-icon btn-text-secondary rounded-pill waves-effect waves-light dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              `<a href="${viewUrl}" class="dropdown-item">View</a>` +
              `<a href="${suspendUrl}" class="dropdown-item">Suspend</a>` +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [2, 'asc'], //set any columns order asc/desc
      dom:
        '<"card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-start"' +
        '<"me-5 ms-n4 pe-5 mb-n6 mb-md-0"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex flex-column align-items-start align-items-sm-center justify-content-sm-center pt-0 gap-sm-4 gap-sm-0 flex-sm-row"lB>>' +
        '>t' +
        '<"row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [7, 10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Product',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ti ti-upload me-1 ti-xs"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                // Customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-export me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-text me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-2"></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7],
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('product-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Product</span>',
          className: 'add-new btn btn-primary ms-2 ms-sm-0 waves-effect waves-light',
          action: function () {
            window.location.href = productAdd;
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['artwork_title'];
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
        // Adding status filter once table initialized
        this.api()
          .columns(-2)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductStatus" class="form-select text-capitalize"><option value="">Status</option></select>'
            )
              .appendTo('.product_status')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + statusObj[d].title + '">' + statusObj[d].title + '</option>');
              });
          });
        // Adding category filter once table initialized
        this.api()
          .columns(3)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="ProductCategory" class="form-select text-capitalize"><option value="">Category</option></select>'
            )
              .appendTo('.product_category')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + categoryObj[d].title + '">' + categoryObj[d].title + '</option>');
              });
          });
        // Adding stock filter once table initialized
        // this.api()
        //   .columns(4)
        //   .every(function () {
        //     var column = this;
        //     var select = $(
        //       '<select id="ProductStock" class="form-select text-capitalize"><option value=""> Stock </option></select>'
        //     )
        //       .appendTo('.product_stock')
        //       .on('change', function () {
        //         var val = $.fn.dataTable.util.escapeRegex($(this).val());
        //         column.search(val ? '^' + val + '$' : '', true, false).draw();
        //       });

        //     column
        //       .data()
        //       .unique()
        //       .sort()
        //       .each(function (d, j) {
        //         select.append('<option value="' + stockObj[d].title + '">' + stockFilterValObj[d].title + '</option>');
        //       });
        //   });
      }
    });
    $('.dataTables_length').addClass('mx-n2');
    $('.dt-buttons').addClass('d-flex flex-wrap mb-6 mb-sm-0');
  }

  // Delete Record
  $('.datatables-products tbody').on('click', '.delete-record', function () {
    dt_products.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
