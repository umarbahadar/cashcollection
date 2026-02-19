<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var previousValue = $('#is_active').val();
$('#is_active').change(function() {
    // Show confirmation dialog
    Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'Changing the status will update the sub category status. Do you want to proceed?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form
            submitForm();
        }else {
            // Reset the value of the select element
            $('#is_active').val(previousValue);
        }
    });
});


</script>