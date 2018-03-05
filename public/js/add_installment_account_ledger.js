$(document).ready(function () {
  $('#years_to_pay').on('paste input',function () {
  	computeLedger();
  });
  $('#mo_interest').on('paste input',function () {
  	computeLedger();
  });
  $('#tcp').on('paste input',function () {
  	computeDp();
  	computeLedger();
  });
  $('#dp_percentage').on('paste input',function () {
  	computeDp();
  	computeLedger();
  });
  $('#reservation_fee').on('paste input',function () {
  	computeDp();
  	computeLedger();
  });
});

function computeDp()
{
	var tcp = parseFloat($('#tcp').val().replace(/,/g,""));
	//var reservation_fee = $('#reservation_fee').val() != "" ? parseFloat($('#reservation_fee').val().replace(/,/g,"")) : 0;
	var dp_percentage = $('#dp_percentage').val() != "" ? parseFloat($('#dp_percentage').val().replace(/,/g,"")) : 0;
	var dp = numberWithCommas(Math.round((tcp * (dp_percentage/100))));
	if(dp != 'NaN') $('#dp').val(dp);
}

function computeLedger()
{
	var tcp = parseFloat($('#tcp').val().replace(/,/g,""));
	var dp = parseFloat($('#dp').val().replace(/,/g,""));
	var years_to_pay = parseFloat($('#years_to_pay').val().replace(/,/g,""));
	var mo_interest = parseFloat($('#mo_interest').val().replace(/,/g,"")) / 100;

	var balance = tcp - dp;
	$('#balance').val(numberWithCommas(balance));

	var loan_payment_terms_in_months = years_to_pay * 12;
	var amortization_factor = mo_interest/(1-(Math.pow((1+mo_interest), (-1 * loan_payment_terms_in_months))));
	
	var mo_amortization = numberWithCommas((balance * amortization_factor).toFixed(2));

	if(mo_amortization != 'NaN') $('#mo_amortization').val(mo_amortization);
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}