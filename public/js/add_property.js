function onPropertyTypeChange(property_type) {
    var selectedText = property_type.options[property_type.selectedIndex].innerHTML;
    var selectedValue = property_type.value;

    $('.residential').hide();
    $('.lot').hide();
    $('.condominium-unit').hide();
    $('.commercial-unit').hide();
    $('.commercial-building').hide();
    
    if(selectedValue == 1 || selectedValue == 2 || selectedValue == 3) {
      $('.residential').show();
    } else if(selectedValue == 4) {
      $('.lot').show();
    } else if(selectedValue == 5) {
      $('.condominium-unit').show();
    } else if(selectedValue == 6) {
      $('.commercial-unit').show();
    } else if(selectedValue == 7) {
      $('.commercial-building').show();
    } 
}

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

/**
* Changed the list of cities/municipalities whenever a province was selected.
*
*/
function onFloorsChange(floors,numFloors){
  var selectedValue = floors.options[floors.selectedIndex].value;
  for(var i = 1; i <= numFloors; i++) {
    $('[id^="floor_area_per_floor['+i+'"]').hide(500);
  }
  for(var i = 1; i <= selectedValue; i++) {
    $('[id^="floor_area_per_floor['+i+'"]').show(750);
  }
}
