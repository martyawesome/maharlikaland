function onProvinceChange(province, cities_municipalities_list){    
  var cities_municipalities = document.getElementById("city_municipality"); 
  $("#city_municipality").empty();
  var selectedValue = province.options[province.selectedIndex].value;
  
  for(var i = 0; i < cities_municipalities_list.length; i++){
    if(selectedValue == cities_municipalities_list[i].province_id){
      var el = document.createElement("option");
      el.textContent = cities_municipalities_list[i].name;
      el.value = cities_municipalities_list[i].id;
      cities_municipalities.appendChild(el);
    }
  }
}


$(document).ready(function () {
  $('#blocks').on('paste input',function () { 
    var lots_num = $('#blocks').val();
    if(window.lots_blocks_num == null){
      window.lots_blocks_num = [];
    }

    var n = $("input[name^='lots_blocks']").length;
    var array = $("input[name^='lots_blocks']");
    if(n>0){
      for(var i=0; i < n; i++) {
        window.lots_blocks_num[i] = array.eq(i).val();
      }
    }

    $("#lots_container").html("");
    if(!isNaN(parseFloat(lots_num)) && isFinite(lots_num)) {
      for(var i=0;i<lots_num;i++) {
        var lot_string = "<div class=\"form-group\" style=\"padding-left:30px;\" id=\"lots_blocks_container["+i+"]\"><label for=\"lots_blocks["+i+"]\">Lot "+(i+1)+"</label><input class=\"form-control\" placeholder=\"Number of blocks\" name=\"lots_blocks["+i+"]\" type=\"text\" id=\"lots_blocks["+i+"]\"></div>";
        $("#lots_container").append(lot_string);
        $("div[id^='lots_blocks_container']").eq(i).hide();
        $("div[id^='lots_blocks_container']").eq(i).show(500);
        $("input[name^='lots_blocks']").eq(i).val(window.lots_blocks_num[i]);
      }
    }
  });

  $('#add_nearby_establishment').click(function(){
    if(window.nearby_establishments_count == null || window.nearby_establishments_count == -1){
      window.nearby_establishments_count = 0;
    } else {
      ++window.nearby_establishments_count;
    }

    var added_nearby_establishment = $('#nearby_establishment').val();
    var added_nearby_establishment_string = "<div class=\"input-group\" style=\"margin-bottom:10px;\" id=\"current_nearby_establishment_container["+window.nearby_establishments_count+"]\"><input type=\"text\" class=\"form-control\" name=\"current_nearby_establishments["+window.nearby_establishments_count+"]\" value=\""+added_nearby_establishment+"\"><span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"button\" id=\"remove_nearby_establishments["+window.nearby_establishments_count+"]\">Remove</button></span></div>";
    $("#nearby_establishments_container").append(added_nearby_establishment_string);
    $("#nearby_establishment").val("");

    $("button[id^='remove_nearby_establishment']").eq(window.nearby_establishments_count).bind('click',function(){
      if($("#nearby_establishments_container").children().length == 1){
        window.nearby_establishments_count = -1;
      }
      $(this).parent().parent().remove();
    });
  });

  $('#add_incentives').click(function(){
    if(window.incentives_count == null || window.incentives_count == -1){
      window.incentives_count = 0;
    } else {
      ++window.incentives_count;
    }

    var added_incentives = $('#incentives').val();
    var added_incentives_string = "<div class=\"input-group\" style=\"margin-bottom:10px;\" id=\"current_incentives_container["+window.incentives_count+"]\"><input type=\"text\" class=\"form-control\" name=\"current_incentives["+window.incentives_count+"]\" value=\""+added_incentives+"\" ><span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"button\" id=\"remove_incentives["+window.incentives_count+"]\">Remove</button></span></div>";
    $("#incentives_container").append(added_incentives_string);
    $("#incentives").val("");

    $("button[id^='remove_incentives']").eq(window.incentives_count).bind('click',function(){
      if($("#incentives_container").children().length == 1){
        window.incentives_count = -1;
      }
      $(this).parent().parent().remove();
    });
  });

  $('#add_model_unit').click(function(){
    if(window.model_units_count == null || window.model_units_count == -1){
      window.model_units_count = 0;
    } else {
      ++window.model_units_count;
    }

    var added_model_unit = $('#model_unit').val();
    var added_model_unit_string = "<div class=\"input-group\" style=\"margin-bottom:10px;\" id=\"current_model_units_container["+window.model_units_count+"]\"><input type=\"text\" class=\"form-control\" name=\"current_model_units["+window.model_units_count+"]\" value=\""+added_model_unit+"\" disabled><span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"button\" id=\"remove_model_units["+window.model_units_count+"]\">Remove</button></span></div>";
    $("#model_units_container").append(added_model_unit_string);
    $("#model_unit").val("");

    $("button[id^='remove_model_units']").eq(window.model_units_count).bind('click',function(){
      if($("#model_units_container").children().length == 1){
        window.model_units_count = -1;
      }
      $(this).parent().parent().remove();
    });
  });

  $('#add_amenities').click(function(){
    if(window.amenities_count == null || window.amenities_count == -1){
      window.amenities_count = 0;
    } else {
      ++window.amenities_count;
    }

    var added_amenities = $('#amenities').val();
    var added_amenities_string = "<div class=\"input-group\" style=\"margin-bottom:10px;\" id=\"current_amenities_container["+window.amenities_count+"]\"><input type=\"text\" class=\"form-control\" name=\"current_amenities["+window.amenities_count+"]\" value=\""+added_amenities+"\"><span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"button\" id=\"remove_amenities["+window.amenities_count+"]\">Remove</button></span></div>";
    $("#amenities_container").append(added_amenities_string);
    $("#amenities").val("");

    $("button[id^='remove_amenities']").eq(window.amenities_count).bind('click',function(){
      if($("#amenities_container").children().length == 1){
        window.amenities_count = -1;
      }
      $(this).parent().parent().remove();
    });
  });

  $('#add_joint_ventures').click(function(){
    if(window.joint_ventures_count == null || window.joint_ventures_count == -1){
      window.joint_ventures_count = 0;
    } else {
      ++window.joint_ventures_count;
    }

    var added_joint_ventures = $('#joint_venture').val();
    var added_joint_ventures_string = "<div class=\"input-group\" style=\"margin-bottom:10px;\" id=\"current_joint_ventures_container["+window.joint_ventures_count+"]\"><input type=\"text\" class=\"form-control\" name=\"current_joint_ventures["+window.joint_ventures_count+"]\" value=\""+added_joint_ventures+"\"><span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"button\" id=\"remove_joint_ventures["+window.joint_ventures_count+"]\">Remove</button></span></div>";
    $("#joint_ventures_container").append(added_joint_ventures_string);
    $("#joint_venture").val("");

    $("button[id^='remove_joint_ventures']").eq(window.joint_ventures_count).bind('click',function(){
      if($("#joint_ventures_container").children().length == 1){
        window.joint_ventures_count = -1;
      }
      $(this).parent().parent().remove();
    });
  });

});

