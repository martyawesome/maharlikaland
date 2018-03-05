$(document).ready(function () {
  $("#delete-button").click(function(){
    $('#confirmation-modal').modal('show');
  });

  $("#sure-confirmation-button").click(function(){
    $.ajax({
        type: "POST",
        url: "/manage/developers/journals/delete/"+journal_id,
        success: function (data) {
          if(data == 1){ 
            $('#success-modal').modal('show');
            $("#success-modal-button").click(function(){
                window.location="/manage/developers/journals/";
            });
          } else {
            $('#invalid-modal-message').html('Something went wrong while deleting. Please, try again.');
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