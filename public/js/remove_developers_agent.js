$(document).ready(function(){
  var all_developers_agents = $(".developer-agent-item");

  /* Loop through all divs with the class developer-agent-item then get the id which is the
     id of the developer_agent object, then add a listener to the div to display a modal for
     the confirmation.
  */ 
  for(var i=0; i<all_developers_agents.length; i++){
      var developer_agent_id = all_developers_agents.eq(i).attr('id');
      $("#"+developer_agent_id).click(generate_handler(developer_agent_id));
  }
});

function generate_handler( j ) {
  return function(event) { 
    removeAgent(j);
  };
}

function removeAgent(id){  
  $('#confirmation-modal').modal('show');
  $("#sure-confirmation-button").click(function(){
      $('#security-code-modal').modal('show');
  });
  $("#submit-security-code-button").click({developer_agent_id:id},function(event){
    $.ajax({
      type: "POST",
      url: "/manage/developers/agents/remove/"+id,
      data: {
        security_code : $('#security_code').val(),
        developer_agent_id :event.data.user_id
      },
      success: function (data) {
        if(data == 1){ 
          window.location="/manage/developers/agents";
        } else {
          if(data == 0){
            $('#invalid-modal-message').html('Invalid security code');
          } else {
            $('#invalid-modal-message').html('Something went wrong when removing the agent. Please, try again later.');
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

