$(document).ready(function(){
  $('#main_agents_container').hide();

  $('#first_name').on('input',function(e) {
      searchAgent();
  });

  $('#last_name').on('input',function(e) {
      searchAgent();
  });

  $('#email').on('input',function(e) {
      searchAgent();
  });

});

function searchAgent(){
  var first_name = $('#first_name').val();
  var last_name = $('#last_name').val();
  var email = $('#email').val();

  if(first_name == "" && last_name == "" && email == "") {
    $("#agents_container").empty();
  }

  $.ajax({
      type: "POST",
      url: "/manage/developers/agents/search",
      data: {
        first_name : $('#first_name').val(),
        last_name: $('#last_name').val(),
        email: $('#email').val()
      },
      success: function (data) {
        if(data == null) {
          $('#main_agents_container').hide();
        } else {
          $('#main_agents_container').show();
          $("#agents_container").empty();

          var agent_string = "<div class='list-group'>";
          for (var i = 0; i < data.length; i++) {
            agent_string += "<div id ='"+data[i].user_id+"' class='list-group-item list-group-item-action search-agent-item' onmouseover=\"this.style.background='#6CCF48';\" onmouseout=\"this.style.background='white';\"><h5 class='list-group-item-heading'>" + data[i].first_name + " " + data[i].last_name +"</h5><p class='list-group-item-text'>"+data[i].user_type+'</br>'+data[i].address+'</br>'+data[i].contact_number+'</br>'+data[i].email+"</p></div>";
          }
          agent_string += "</div>"

          $("#agents_container").append(agent_string);

          for(i = 0; i < data.length; i++){
             $("#" + data[i].user_id).click(generate_handler(data[i].user_id));
          }

        }
      },
      error: function (data) {
        alert("Something went wrong with the server. Please refresh the page or try again later.");
      }
  });
}

function generate_handler( j ) {
  return function(event) { 
      confirmUser(j);
  };
}

function confirmUser(id){  
  $('#confirmation-modal').modal('show');
  $("#sure-confirmation-button").click(function(){
      $('#security-code-modal').modal('show');
  });
  $("#submit-security-code-button").click({user_id:id},function(event){
    $.ajax({
      type: "POST",
      url: "/manage/developers/agents/add",
      data: {
        security_code : $('#security_code').val(),
        user_id :event.data.user_id
      },
      success: function (data) {
        if(data == 1){ 
          $('#success-modal').modal('show');
        } else {
          if(data == 3){
            $('#invalid-modal-message').html('The agent is already in your system');
          } else if(data == 0){
            $('#invalid-modal-message').html('Invalid security code');
          } else {
            $('#invalid-modal-message').html('Something went wrong with the server. Please, try again later.');
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

