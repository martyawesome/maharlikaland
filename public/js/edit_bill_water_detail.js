$(document).ready(function () {
  $('#consumption').on('paste input',function () {
  	var new_bill = $('#consumption').val() * cost_per_consumption;
    $('#bill').val(new_bill);
  });
});