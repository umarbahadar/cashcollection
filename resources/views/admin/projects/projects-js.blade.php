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
        dataTable = $('#items_table').DataTable({
        processing: true,
        serverside: true,
        data: data,
        columns: [
            { data: 'serialNumber', title: 'Sr.#' },
            {
                data: 'image',
                title: 'Image',
                render: function(data, type, full, meta) {
                    if (data) {
                        return `<img src="/storage/images/projects/${data}" alt="Item Image" class="img-thumbnail" width="50" height="50">`;
                    } else {
                        return '<span class="text-muted">No Image</span>';
                    }
                }
            },
            { data: 'name', title: 'Project' },
            { data: 'category', title: 'Category' },
            

           
            {
                data: 'id',
                render: function(data, type, full, meta) {
                    var editUrl = "".replace(':id', data);
                    var editUrl = "{{ route('projects.edit', ':id') }}".replace(':id', data);

                    var detailUrl = "{{ route('projects.view', ':id') }}".replace(':id', data);

                    
                    return `<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown-${data}" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionDropdown-${data}">
                                <li><a class="dropdown-item edit-term-btn" href="${detailUrl}">View Details</a></li>
                                <li><a class="dropdown-item edit-term-btn" href="${editUrl}">Edit</a></li>
                                <li><a class="dropdown-item delete-product-btn text-danger" data-id="${data}" href="#">Delete</a></li>

                            </ul>`;
                }
            }
        ]
    });

    }


      // Function to fetch data and initialize DataTable
      function fetchDataAndInitializeTable() {
        $('#loading').show();

        $.ajax({
            type: "get",
            url: "{{ route('projects.getall') }}",
            success: function(data) {
                $('#loading').hide();

                if (data.error) {
                    toastr.error('Oops, Error: ' + data.error);
                } else {
                    initializeDataTable(data.data);
                }
            },
            error: function(request, status, error) {
                $('#loading').hide();

                toastr.error('Oops, Error: ' + request.responseText + ' :(');
            }
        });
    }

    // Function to fetch filtered data and initialize DataTable
    $(document).ready(function() {
        function fetchFilteredDataAndInitializeTable() {
          
            var categoryID = $('#category_id').val();
            var itemID = $('#id').val();

            $('#loading').show(); // Show the loader
            $.ajax({
                type: "GET",
                url: "{{ route('projects.getall_filtered') }}",
                data: {
                   
                    id: itemID,
                    category_id:categoryID
                },
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

        $('#filter_form').submit(function(event) {
            event.preventDefault();
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


    //Product dELETION
     //Delete The Category
     $(document).on('click', '.delete-product-btn', function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var productId = $(this).data('id'); // Get the category ID from the data attribute

        // Call the deleteCategory function with the category ID
        deleteCategory(productId);
    });

    // New Deletion Function
    function deleteCategory(productId) {
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

            // Send AJAX request to delete the category
            $.ajax({
                type: "DELETE",
                url: "{{ route('projects.destroy', ':id') }}".replace(':id', productId),
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
                        text: 'An issue occurred while deleting the product.' + request.responseJSON.msg,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}



    </script>