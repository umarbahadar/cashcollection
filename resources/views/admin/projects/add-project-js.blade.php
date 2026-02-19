<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
    });

    // Manage the variants dynamically
    $(document).ready(function () {
    var hasVariantsSelect = $('#has_variants');
    var variantSection = $('#variant_section');
    var variantsContainer = $('#variants_container');
    var priceRow = $('#price_row');

    // Toggle visibility based on selection
    hasVariantsSelect.on('change', function () {
        if ($(this).val() === '1') {
            variantSection.show();
            priceRow.hide(); // Hide price field
            variantsContainer.html(''); // Clear variants
            addVariantRow(); // Add an initial row
        } else {
            variantSection.hide();
            variantsContainer.html(''); // Clear variants
            priceRow.show(); // Show price field
        }
    });

    // Add Variant Row
    $('#add_variant_row').click(function () {
        addVariantRow();
    });

    // Remove Variant Row
    $(document).on('click', '.remove-variant-row', function () {
        $(this).closest('.variant-row').remove();
    });

    function addVariantRow() {
        var newRow = `
            <div class="row mb-2 variant-row">
                <div class="col-lg-5">
                    <input type="text" class="form-control" name="variant_name[]" placeholder="Variant Name">
                </div>
                <div class="col-lg-5">
                    <input type="text" class="form-control" name="variant_price[]" placeholder="Variant Price">
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-danger remove-variant-row"><i class="bi bi-trash3"></i></button>
                </div>
            </div>`;
        variantsContainer.append(newRow);
    }

    // Initialize on page load if necessary
    if (hasVariantsSelect.val() === '1') {
        variantSection.show();
        priceRow.hide();
        addVariantRow();
    }
});



// Upload Image Style JS Starts Here ******************************
document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    }); 
// Upload Image Style JS Ends Here ******************************

    //Add New User AJAX

    $('#projectForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $("#loading").show();
            $.ajax({
                type: "POST",
                url: "{{ route('projects.save') }}",
                dataType: 'json',
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                contentType: false, // Prevent jQuery from overriding the Content-Type header
                success: function(data) {
                    $("#loading").hide();
                    if (data.status === 'success') {
                            Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.msg,
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = "{{ route('projects.index') }}";
                        });
                       
                    } else {
                        toastr.error(data.msg);
                    }
                },
                error: function(request, status, error) {
                    $("#loading").hide();
                    toastr.error(request.responseText);
                }
            });
    });

</script>