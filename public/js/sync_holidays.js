$(document).ready(function () {
  $().DataTable();
  $("#sync-button").click(function(){
    $('#confirmation-modal').modal('show');
  });
  $("#sure-confirmation-button").click(function(){
    $('#security-code-modal').modal('show');
  });

  $("#submit-security-code-button").click(function(){
    $('.cssload-loader').show(); 
    $.ajax({
        type: "POST",
        url: "/manage/developers/payroll/holidays/sync",
        data: {
          security_code : $('#security_code').val()
        },
        success: function (data) {
          var loaders = document.getElementsByClassName('cssload-loader'), i;
          for (var i = 0; i < loaders.length; i ++) {
              loaders[i].style.display = 'none';
          }

          if(data == 1){ 
            $('#success-modal').modal('show');
            $("#success-modal-button").click(function(){
                window.location="/manage/developers/payroll/holidays/";
            });
          } else {
            if(data == 0){
              $('#invalid-modal-message').html('Invalid security code');
            } else {
              $('#invalid-modal-message').html('Something went wrong while deleting. Please, try again.');
            }
            $('#invalid-modal').modal('show');
          } 
        },
        error: function (data) {
          $('#invalid-modal-message').html('Something went wrong with the server. Please, try again later.');
          $('#invalid-modal').modal('show');
        }
    });
  });
});