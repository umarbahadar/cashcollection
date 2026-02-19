<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function updateAccountVisibility(selectedType) {
    $('#userAccountsWrapper, #clientAccountsWrapper').hide();
    if (!selectedType) {
        return;
    }
    if (selectedType === 'cash' || selectedType === 'cheque') {
        return;
    }
    $('#userAccountsWrapper, #clientAccountsWrapper').fadeIn(150);
}

$(document).ready(function () {
    const existingAllocations = @json($payment->allocations->map(function ($alloc) {
        return [
            'invoice_id' => $alloc->client_invoice_id,
            'amount' => (float) $alloc->amount,
        ];
    }));

    const imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imagePreview').innerHTML =
                        '<img src="' + e.target.result + '" alt="Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    const currentMethodType = $('#payment_method_id option:selected').data('type') || '';
    updateAccountVisibility(currentMethodType);

    $('#payment_method_id').on('change', function () {
        const newType = $(this).find(':selected').data('type') || '';
        updateAccountVisibility(newType);
    });

    function recalculateAllocations() {
        let remaining = parseFloat($('#amount').val()) || 0;

        $('#clientInvoicesTable tr').each(function () {
            const $row = $(this);
            const $checkbox = $row.find('.invoice-checkbox');
            const $amountInput = $row.find('.invoice-amount');
            const max = parseFloat($amountInput.attr('max')) || 0;

            if ($row.data('manual')) {
                const manualAmount = parseFloat($amountInput.val()) || 0;
                if ($checkbox.is(':checked')) {
                    remaining -= manualAmount;
                }
                return;
            }

            if (remaining <= 0) {
                $checkbox.prop('checked', false);
                $amountInput.val('');
                return;
            }

            const apply = Math.min(max, remaining);

            $checkbox.prop('checked', true);
            $amountInput.val(apply.toFixed(2));
            remaining -= apply;
        });
    }

    function resetAutoRows() {
        $('#clientInvoicesTable tr').each(function () {
            if (!$(this).data('manual')) {
                $(this).find('.invoice-checkbox').prop('checked', false);
                $(this).find('.invoice-amount').val('');
            }
        });
    }

    function preloadAllocations() {
        existingAllocations.forEach(function (alloc) {
            const $row = $('tr[data-invoice-id="' + alloc.invoice_id + '"]');
            if ($row.length) {
                $row.find('.invoice-checkbox').prop('checked', true);
                $row.find('.invoice-amount').val(alloc.amount.toFixed(2));
            }
        });
    }

    function loadClientContext(clientId, isInitial) {
        if (!clientId) {
            return;
        }

        $.ajax({
            url: "{{ route('receive-payment.getClientContext') }}",
            type: "GET",
            data: {
                client_id: clientId,
                payment_id: $('#payment_id').val()
            },
            beforeSend: function () {
                $('#clientInvoicesTable').html('<tr><td colspan="9" class="text-center text-muted">Loading invoices...</td></tr>');
                if (!isInitial) {
                    $('#recentPayments').html('<li class="list-group-item text-muted">Loading...</li>');
                }
            },
            success: function (response) {
                if (!response.status) {
                    toastr.error('Failed to load client data');
                    return;
                }

                $('#clientBalance').text(response.balance || '0.00');

                let rowsHtml = '';
                if (!response.unpaid_invoices || !response.unpaid_invoices.length) {
                    rowsHtml = '<tr><td colspan="9" class="text-center text-muted">No outstanding invoices</td></tr>';
                } else {
                    response.unpaid_invoices.forEach(function (inv) {
                        const status = inv.status_name || 'Due';
                        const normalized = status.toLowerCase();
                        let badgeClass = 'secondary';
                        if (normalized === 'due') {
                            badgeClass = 'info';
                        } else if (normalized === 'partial') {
                            badgeClass = 'warning';
                        } else if (normalized === 'overdue') {
                            badgeClass = 'danger';
                        }

                        const rawMax = inv.editable_balance_due ?? inv.balance_due;
                        const realMax = parseFloat(rawMax) || 0;

                        rowsHtml += ''
                            + '<tr data-invoice-id="' + inv.id + '" data-manual="false">'
                            + '<td><input type="checkbox" class="invoice-checkbox" value="' + inv.id + '"></td>'
                            + '<td>' + (inv.invoice_number || '-') + '</td>'
                            + '<td>' + (inv.invoice_date || '-') + '</td>'
                            + '<td>' + (inv.due_date || '-') + '</td>'
                            + '<td><span class="badge bg-' + badgeClass + '">' + status + '</span></td>'
                            + '<td>' + inv.formatted_total + '</td>'
                            + '<td>' + inv.formatted_paid + '</td>'
                            + '<td>' + inv.formatted_balance + '</td>'
                            + '<td>'
                            + '<input type="number" class="form-control form-control-sm invoice-amount"'
                            + ' min="0" max="' + realMax.toFixed(2) + '" step="0.01">'
                            + '</td>'
                            + '</tr>';
                    });
                }

                $('#clientInvoicesTable').html(rowsHtml);

                const payments = response.recent_payments || [];
                const payHtml = payments.length
                    ? payments.map(function (p) {
                        const amount = parseFloat(p.amount) || 0;
                        return ''
                            + '<li class="list-group-item d-flex justify-content-between">'
                            + '<span>' + (p.payment_number || '') + '</span>'
                            + '<strong>' + amount.toFixed(2) + '</strong>'
                            + '</li>';
                    }).join('')
                    : '<li class="list-group-item text-muted">No recent payments</li>';
                $('#recentPayments').html(payHtml);

                if (isInitial) {
                    const userAccounts = response.user_accounts || [];
                    const userHtml = userAccounts.map(function (acc) {
                        const isChecked = acc.id == {{ json_encode($payment->user_account_id ?? null) }};
                        return ''
                            + '<label class="account-card">'
                            + '<input type="radio" name="user_account_id" value="' + acc.id + '"' + (isChecked ? ' checked' : '') + '>'
                            + '<div class="account-card-body">'
                            + '<div class="account-card-header">'
                            + '<span class="method">' + (acc.payment_method?.name || '-') + '</span>'
                            + (acc.is_default ? '<span class="badge bg-success">Default</span>' : '')
                            + '</div>'
                            + (acc.bank ? '<div class="account-row"><span class="label">Bank</span><span class="value">' + acc.bank.name + '</span></div>' : '')
                            + '<div class="account-row"><span class="label">Account Title</span><span class="value">' + (acc.account_title || '-') + '</span></div>'
                            + (acc.account_number ? '<div class="account-row"><span class="label">IBAN / No</span><span class="value">' + acc.account_number + '</span></div>' : '')
                            + '</div>'
                            + '</label>';
                    }).join('');
                    $('#userAccountsList').html(userHtml);

                    const clientAccounts = response.client_accounts || [];
                    const clientHtml = clientAccounts.map(function (acc) {
                        const isChecked = acc.id == {{ json_encode($payment->client_account_information_id ?? null) }};
                        return ''
                            + '<label class="account-card client">'
                            + '<input type="radio" name="client_account_information_id" value="' + acc.id + '"' + (isChecked ? ' checked' : '') + '>'
                            + '<div class="account-card-body">'
                            + '<div class="account-card-header">'
                            + '<span class="method">' + (acc.payment_method?.name || '-') + '</span>'
                            + (acc.is_default ? '<span class="badge bg-primary">Default</span>' : '')
                            + '</div>'
                            + (acc.bank ? '<div class="account-row"><span class="label">Bank</span><span class="value">' + acc.bank.name + '</span></div>' : '')
                            + '<div class="account-row"><span class="label">Account Title</span><span class="value">' + (acc.account_title || '-') + '</span></div>'
                            + (acc.account_number ? '<div class="account-row"><span class="label">IBAN / No</span><span class="value">' + acc.account_number + '</span></div>' : '')
                            + '</div>'
                            + '</label>';
                    }).join('');
                    $('#clientAccountsList').html(clientHtml);

                    const selectedType = $('#payment_method_id option:selected').data('type') || '';
                    updateAccountVisibility(selectedType);

                    setTimeout(function () {
                        preloadAllocations();
                        recalculateAllocations();
                    }, 100);
                } else {
                    recalculateAllocations();
                }
            },
            error: function () {
                toastr.error('Error loading client context');
            }
        });
    }

    loadClientContext($('#client_id').val(), true);

    $('#client_id').on('change', function () {
        loadClientContext($(this).val(), false);
    });

    $(document).on('input', '.invoice-amount', function () {
        const $row = $(this).closest('tr');
        $row.data('manual', true);
        $row.find('.invoice-checkbox').prop('checked', true);
        recalculateAllocations();
    });

    $(document).on('change', '.invoice-checkbox', function () {
        const $row = $(this).closest('tr');

        if (!$(this).is(':checked')) {
            $row.data('manual', true);
            $row.find('.invoice-amount').val('');
        } else {
            $row.data('manual', false);
        }

        recalculateAllocations();
    });

    $('#amount').on('input', function () {
        resetAutoRows();
        recalculateAllocations();
    });

    $(document).on('change', '#selectAllInvoices', function () {
        const checked = $(this).is(':checked');
        $('#clientInvoicesTable tr').each(function () {
            const $row = $(this);
            $row.find('.invoice-checkbox').prop('checked', checked);
            $row.data('manual', true);
            if (checked) {
                const max = parseFloat($row.find('.invoice-amount').attr('max')) || 0;
                $row.find('.invoice-amount').val(max ? max.toFixed(2) : '');
            } else {
                $row.find('.invoice-amount').val('');
            }
        });
        recalculateAllocations();
    });

    $('#EditClientPaymentForm').on('submit', function (e) {
        e.preventDefault();

        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Updating...');

        if (!$('#client_id').val()) {
            toastr.error('Please select a client');
            $btn.prop('disabled', false).text('Update Payment');
            return false;
        }
        if (!$('#payment_method_id').val()) {
            toastr.error('Please select a payment method');
            $btn.prop('disabled', false).text('Update Payment');
            return false;
        }
        if (parseFloat($('#amount').val() || 0) <= 0) {
            toastr.error('Amount must be greater than 0');
            $btn.prop('disabled', false).text('Update Payment');
            return false;
        }

        if ($('#userAccountsWrapper').is(':visible') && !$('input[name="user_account_id"]:checked').length) {
            toastr.error('Please select Pay From (our) account');
            $btn.prop('disabled', false).text('Update Payment');
            return false;
        }
        if ($('#clientAccountsWrapper').is(':visible') && !$('input[name="client_account_information_id"]:checked').length) {
            toastr.error('Please select Pay To (client) account');
            $btn.prop('disabled', false).text('Update Payment');
            return false;
        }

        const formData = new FormData(this);
        formData.delete('allocations');

        let index = 0;

        $('#clientInvoicesTable tr').each(function () {
            const $chk = $(this).find('.invoice-checkbox');

            if ($chk.is(':checked')) {
                const amt = parseFloat($(this).find('.invoice-amount').val()) || 0;

                if (amt > 0) {
                    formData.append('allocations[' + index + '][invoice_id]', $chk.val());
                    formData.append('allocations[' + index + '][amount]', amt.toFixed(2));
                    index++;
                }
            }
        });

        $.ajax({
            url: "{{ route('receive-payment.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire('Success', res.msg, 'success')
                        .then(function () {
                            window.location.href = "{{ route('receive-payment.index') }}";
                        });
                } else {
                    toastr.error(res.msg || 'Update failed');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const firstKey = Object.keys(errors)[0];
                    toastr.error(errors[firstKey][0]);
                    return;
                }

                if (xhr.status === 419) {
                    toastr.error('Session expired. Please refresh the page.');
                    return;
                }

                if (xhr.responseJSON && xhr.responseJSON.msg) {
                    toastr.error(xhr.responseJSON.msg);
                    return;
                }

                toastr.error('Unexpected server error occurred.');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Update Payment');
            }
        });
    });
});
</script>
