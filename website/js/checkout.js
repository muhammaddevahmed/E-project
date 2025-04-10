
$(document).ready(function() {
    // Function to hide all payment forms
    function hideAllForms() {
        $('.payment-form').hide();
    }

    // Show the selected payment form
    $('input[name="payment_method"]').on('change', function() {
        hideAllForms();
        switch ($(this).attr('id')) {
            case 'cash-on-delivery':
                $('#cash-on-delivery-message').show();
                break;
            case 'check-payment':
                $('#check-payment-form').show();
                break;
            case 'credit-card':
                $('#card-payment-form').show();
                break;
            default:
                hideAllForms();
        }
    });

    // Hide all forms initially
    hideAllForms();
});

$('form').on('submit', function(e) {
    var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
    if (selectedPaymentMethod === 'check_payment') {
        if ($('input[name="check_number"]').val() === '' || $('input[name="bank_name"]').val() === '') {
            alert('Please fill out all check payment details.');
            e.preventDefault();
        }
    } else if (selectedPaymentMethod === 'credit_card') {
        if ($('input[name="card_number"]').val() === '' || $('input[name="expiry_date"]').val() === '' || $('input[name="cvv"]').val() === '') {
            alert('Please fill out all card payment details.');
            e.preventDefault();
        }
    }
});
