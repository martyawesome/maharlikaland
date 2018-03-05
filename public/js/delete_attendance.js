$(document).ready(function () {
  $("#delete-button").click(function(){
    $('#confirmation-modal').modal('show');
  });
  $("#sure-confirmation-button").click(function(){
    $('#security-code-modal').modal('show');
  });

  $("#submit-security-code-button").click(function(){
    $.ajax({
        type: "POST",
        url: "/manage/developers/attendance/"+date+"/"+attendance+"/delete",
        data: {
          security_code : $('#security_code').val()
        },
        success: function (data) {
          if(data == 1){ 
            $('#success-modal').modal('show');
            $("#success-modal-button").click(function(){
                window.location="/manage/developers/attendance/"+date;
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