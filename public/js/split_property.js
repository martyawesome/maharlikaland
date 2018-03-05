$(document).ready(function () {
	$("#lots").on('paste keyup', function () {
    	var lots_num = $('#lots').val();
    	onLotsChange(lots_num);
    });

    $("#submit-form-button").click(function(){
    	var total_new_lots = $("input[name^='lots_lot_area']").length;

	    var array = $("input[name^='lots_lot_area']");
	    var total_input = 0;
	    for(var i = 0; i < total_new_lots; i++) {
	    	if(array.eq(i).val() != "") {
	    		total_input += parseFloat(array.eq(i).val());
	    	}	
	    }

	    if(lot_area == total_input) {
	    	$('#security-code-modal').modal('show');
	    	$("#submit-security-code-button").click(function(){
		      $.ajax({
		        type: "POST",
		        url: base_url + "/manage/developers/projects/"+project_slug+"/"+property_slug+"/split/validate",
		        data: {
		          security_code : $('#security_code').val()
		        },	
		        success: function (data) {
		        	if(data == 1){ 
		            	$('form#myForm').submit();
		          	} else {
		            	$('#invalid-security-code-modal').modal('show');
		        	}
		        },
		        error: function (data) {
		          alert("Something went wrong with the server. Please, try again.");
		        }
		      });       
		    });

	    	return false;
	    	
	    } else {
	    	$('#dangerModal').modal();
	    }

	});

});

function onLotsChange(lots_num)
{
	if ((event.keyCode != 8 || event.keyCode != 46) && !isNaN(parseFloat(lots_num)) && isFinite(lots_num) && lots_num < 2) {
	    $('#dangerModal').modal();
	    $("#lots_container").html("");
	}

	if(!isNaN(parseFloat(lots_num)) && isFinite(lots_num) && lots_num >= 2) {
	    if(window.lots_blocks_num == null){
	      window.lots_blocks_num = [];
	    }

	    var n = $("input[name^='lots_lot_area']").length;
	    var array = $("input[name^='lots_lot_area']");
	    if(n>0){
	      for(var i=0; i < n; i++) {
	        window.lots_blocks_num[i] = array.eq(i).val();
	      }
	    }

        $("#lots_container").html("");
        if(!isNaN(parseFloat(lots_num)) && isFinite(lots_num)) {
	      var current_char = 'A';
	      for(var i=0;i<lots_num;i++) {
	      	if(i > 0) {
	      		current_char = nextChar(current_char);
	      	}

	        var lot_string = "<div class=\"form-group\" style=\"padding-left:30px;\" id=\"new_lots_container["+i+"]\"><label for=\"lots_lot_area["+i+"]\">Lot "+base_lot_number+ "-" + current_char + " </label><input class=\"form-control\" placeholder=\"Lot Area\" name=\"lots_lot_area["+i+"]\" type=\"text\" id=\"lots_lot_area["+i+"]\"></div>";
	        $("#lots_container").append(lot_string);
	        $("div[id^='new_lots_container']").eq(i).hide();
	        $("div[id^='new_lots_container']").eq(i).show(500);
	        $("input[name^='lots_lot_area']").eq(i).val(window.lots_blocks_num[i]);
	      }
	    }
	}
}

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}