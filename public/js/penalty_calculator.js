$(document).ready(function () {
  $("#calculate-form-button").click(function(){
  	var ma = parseFloat($('#ma').val().replace(/,/g,""));
  	
  	var compound_counter = 100;
    var next_penalty_base = ma + ma * penalty_percentage;
    var penalties = [];
    penalties[0] = ma * penalty_percentage;
    for(var i=1;i<=compound_counter;i++) {
    	 next_penalty = next_penalty_base * penalty_percentage; 
        next_penalty_base += next_penalty;
        //$penalties[$i] = $penalties[$i-1] + $next_penalty;
        penalties[i] = next_penalty;
    }  
    
    for(var i=0;i< penalties.length ;i++) {
    	var penalty_string = "<div class=\"form-group\" style=\"padding-left:30px;\"><b>Php "+parseFloat(penalties[i]).toFixed(2)+"</b></div>";	        
    	$("#penalties_container").append(penalty_string);
  	}

  });
});