function chooseObject(objectId) {
  var idIsFound = false;
  for(var i = 0; i<window.objectIds.length; i++) {
    if(window.objectIds[i] == objectId) {
      idIsFound = true;
      break;
    }
  }
  if(idIsFound) {
    $('#' + objectId).css('background','white');
     window.objectIds = jQuery.grep(window.objectIds, function(value) {
      return value != objectId;
    });
  } else {
    $('#' + objectId).css('background','tomato');
    window.objectIds.push(objectId);
  }
  if(window.objectIds.length == 0) {
    $("#delete-objects-button").attr("disabled", true);
    $("#delete-objects-button").off("click");
  } else {
    $("#delete-objects-button").attr("disabled", false);
    $("#delete-objects-button").click(function(){
        $('#confirmation-modal').modal('show');
    });
    $("#sure-confirmation-button").click(function(){
        $('#security-code-modal').modal('show');
    });
    $("#success-modal-button").click(function(){
       window.location="/manage/developers/marketing/promotional_materials/images";
    });
    $("#submit-security-code-button").click(function(){
      var objectIds = btoa(JSON.stringify(window.objectIds));
      $.ajax({
          type: "POST",
          url: "/manage/developers/marketing/promotional_materials/images/delete/"+objectIds,
          data: {
            security_code : $('#security_code').val()
          },
          success: function (data) {
            if(data == 1){ 
              $('#success-modal-message').html('The promotional images were successfully deleted');
              $('#success-modal').modal('show');
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
  }
}