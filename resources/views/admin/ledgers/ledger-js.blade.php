
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
    });

    var dataTable;
    // Initialize DataTable
    function initializeDataTable(data) {
        if (dataTable) {
            //Destroy it first
            dataTable.destroy();
        }

        // Add a new property to each data item to hold the serial number
        data.forEach((item, index) => {
            item.serialNumber = index + 1;
        });
        dataTable = $('#collections_table').DataTable({
        processing: true,
        serverside: true,
        data: data,
        columns: [
            { 
                data: 'serialNumber', 
                title: 'Sr.#',
                width: '5%'
            },

            { 
                data: 'agent', 
                title: 'Agent',
                className: 'fw-bold'
            },

            { 
                data: 'account', 
                title: 'Account'
            },

            { 
                data: 'type', 
                title: 'Type',
                render: function(data) {
                    if (data === 'credit') {
                        return '<span class="badge bg-success">Credit</span>';
                    } else if (data === 'debit') {
                        return '<span class="badge bg-danger">Debit</span>';
                    }
                    return '--';
                }
            },

            { 
                data: 'collection_date', 
                title: 'Collection Date'
            },

            { 
                data: 'amount', 
                title: 'Amount',
                className: 'text-end fw-semibold'
            },

            {
                data: 'id',
                title: 'Action',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    const detailUrl = "{{ route('ledger.pdf', ':id') }}".replace(':id', data);
                   

                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" 
                                    type="button" 
                                    id="actionDropdown-${data}" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionDropdown-${data}">
                                <li>
                                    <a class="dropdown-item" href="${detailUrl}" target="_blank">
                                        View PDF
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                               
                            </ul>
                        </div>`;
                }
            }
        ]
    });


    }

      // Function to fetch data and initialize DataTable
      function fetchDataAndInitializeTable() {
        $('#loading').show(); // Show the loader
        $.ajax({
            type: "get",
            url: "{{ route('ledger.getall') }}",
            success: function(data) {
                $('#loading').hide(); // Hide the loader
                if (data.error) {
                    toastr.error('Oops, Error: ' + data.error);
                } else {
                    initializeDataTable(data.data);
                }
            },
            error: function(request, status, error) {
                $('#loading').hide(); // Hide the loader
                toastr.error('Oops, Error: ' + request.responseText + ' :(');
            }
        });
    }

    // Function to fetch filtered data and initialize DataTable
    $(document).ready(function() {
        $(document).ready(function() {
        function fetchFilteredDataAndInitializeTable() {
            var clientID        = $('#client_id').val();
            var paymentNumber   = $('#payment_number').val();
            var statusID        = $('#status_id').val();
            var dateFrom        = $('#date_from').val();
            var dateTo          = $('#date_to').val();

            $('#loading').show();
            $.ajax({
                type: "GET",
                url: "{{ route('ledger.getall_filtered') }}",
                data: {
                    client_id: clientID,
                    payment_number: paymentNumber,
                    status_id: statusID,
                    from_date: dateFrom,
                    to_date: dateTo
                },
                success: function(data) {
                    $('#loading').hide();
                    if (data.error) {
                        toastr.error(data.error);
                    } else {
                        initializeDataTable(data.data); // Your DataTable function
                    }
                },
                error: function(request, status, error) {
                    $('#loading').hide();
                    toastr.error(request.responseText);
                }
            });
        }

        $('#filter_form').submit(function(e) {
            e.preventDefault();
            fetchFilteredDataAndInitializeTable();
        });

        // Optionally, load all data on page load
        fetchFilteredDataAndInitializeTable();
    });

       
    });

    // Function to fetch filtered data and initialize DataTable ends here

    $(document).ready(function() {
        // Call the function to fetch data and initialize DataTable on page load
        fetchDataAndInitializeTable();

    });

       // Function to reload DataTable after add, edit and delete processes
       function reloadDataTable() {
        // Clear & Redraw 
        dataTable.clear().rows.add(fetchDataAndInitializeTable()).draw(false);
    }


    $(document).on('click', '.delete-category-btn', function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var subCategoryId = $(this).data('id'); // Get the category ID from the data attribute

        // Call the deleteCategory function with the category ID
        deleteSubCategory(subCategoryId);
    });

    // New funtion for deleting sub category
    function deleteSubCategory(subCategoryId) {
    // Show confirmation dialog
    Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'This action cannot be undone. Do you want to proceed?',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#loading').show(); // Show the loader
            // Send AJAX request to delete the subcategory
            $.ajax({
                type: "DELETE",
                url: "{{ route('ledger.destroy', ':id') }}".replace(':id', subCategoryId),
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#loading').hide(); // Hide the loader
                    if (data.status === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.msg,
                            confirmButtonText: 'OK'
                        }).then(function() {
                            // Reload the page or perform any other action
                            window.location.reload();
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msg,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(request, status, error) {
                    $('#loading').hide(); // Hide the loader
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the bcategory.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}


</script>