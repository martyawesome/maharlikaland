$(document).ready(function () {
	$('#ma_covered_date').datepicker({
	    format: "MM, yyyy",
	    startView: "months", 
	    minViewMode: "months"
	});

	paymentTypeOnChange();
    amountPaidOnChange();

	$("#payment_type").change(function () {
        paymentTypeOnChange();
    });
    $("#amount_paid").on('paste input', function () {
        amountPaidOnChange();
    });
});

function amountPaidOnChange()
{
    var amount_paid = parseFloat($('#amount_paid').val().replace(/,/g,""));
    var payment_type = $('#payment_type option:selected').val();
    if(amount_paid >= (mo_amortization*6) && payment_type == ma) {
        $('#balloon_payment_container').show();
    } else {
        $('#balloon_payment_container').hide();   
    }
}

function paymentTypeOnChange()
{
	var payment_type = $('#payment_type option:selected').val();
	if(payment_type == ma) {
    	$('#ma_covered_date_container').show();
    	$('#with_interest_container').show();
    } else if(payment_type == penalty_fee){
        $('#ma_covered_date_container').show();
        $('#with_interest_container').show();
        $('#details_of_payment_container').hide();
        $('#payment_date_container').hide();
        $('#or_no_container').hide();
        $('#amount_paid_container').hide();
    } else if(payment_type == full_payment){
        $('#payment_date_container').show();
    	$('#ma_covered_date_container').hide();
    	$('#with_interest_container').hide();
        $('#amount_paid_container').hide();
    } else if(payment_type == bank_finance_payment) {
        $('#amount_paid_container').show();
        $('#with_interest_container').hide();
        $('#payment_date_container').show();
        $('#or_no_container').show();
    } else {
        $('#ma_covered_date_container').hide();
        $('#with_interest_container').hide();
    }
}

