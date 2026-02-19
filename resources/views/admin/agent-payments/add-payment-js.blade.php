
<script>

    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


    $('#CashCollectionForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $("#loading").show();
            $.ajax({
                type: "post",
                url: "{{ route('receive-payment.save') }}",
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    $("#loading").hide();
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.msg,
                            allowOutsideClick: false,
                            confirmButtonText: 'OK'
                        }).then(function() {
                            $('#CashCollectionForm')[0].reset();
                            window.location.href = "{{ route('receive-payment.index') }}";
                        });
                    } else {
                        toastr.error('Oops, Error: ' + data.msg);
                    }
                },
                error: function(xhr) {
                    $("#loading").hide();

                    // If validation error (422)
                    if (xhr.status === 422) {
                        let response = xhr.responseJSON;
                        if (response && response.msg) {
                            toastr.warning(response.msg); // Friendly warning
                        } else if (response && response.errors) {
                            // Show first validation message
                            let firstError = Object.values(response.errors)[0][0];
                            toastr.warning(firstError);
                        }
                    } else {
                        // Other errors
                        toastr.error('Oops! Something went wrong. Please try again.');
                    }
                }
            });
        });

         
    });
    </script>
