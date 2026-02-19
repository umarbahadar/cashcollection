
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
                title: 'Date'
            },

            { 
                data: 'amount', 
                title: 'Amount',
                className: 'text-end fw-semibold'
            },

            {
                data: 'id',
                render: function(data, type, full, meta) {
                    var editUrl = "".replace(':id', data);
                    var editUrl = "{{ route('receive-payment.edit', ':id') }}".replace(':id', data);
                    var detailUrl = "{{ route('receive-payment.view', ':id') }}".replace(':id', data);

                    
                    return `<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown-${data}" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionDropdown-${data}">
                                
                                <li><a class="dropdown-item edit-term-btn" href="${detailUrl}">View Details</a></li>
                                
                            </ul>`;
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
            url: "{{ route('receive-payment.getall') }}",
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
         function fetchFilteredDataAndInitializeTable() {
            var clientID   = $('#client_id').val();
            var dateFrom   = $('#date_from').val();
            var dateTo     = $('#date_to').val();

            $('#loading').show(); // Show loader
            $.ajax({
                type: "GET",
                url: "{{ route('receive-payment.getall_filtered') }}",
                data: {
                    client_id: clientID,
                    date_from: dateFrom,
                    date_to: dateTo
                },
                success: function(data) {
                    $('#loading').hide(); // Hide loader
                    if (data.error) {
                        toastr.error(data.error);
                    } else {
                        initializeDataTable(data.data);
                    }
                },
                error: function(request, status, error) {
                    $('#loading').hide();
                    toastr.error(request.responseText);
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



</script>