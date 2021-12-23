/////////////////////////////////////////
/// globals
/////////////////////////////////////////
MAIN_SITE="http://foodster.yumdev.ninja";
MAIN_API=MAIN_SITE+"/api/";
MAIN_IMAGES=MAIN_SITE+"/upload/";
index_for_table_dishes_result=1000;
order_dish_modal_current_page_nom = 1;
var order_dish_ws_parameters_array = [];
/////////////////////////////////////////
///
/////////////////////////////////////////

function addOrderIfNeedDish(dishId,is_isset_in_array_need_to_check) {
	//flag_contained=false;
	//needRemove=true;
	//если есть то ставим галочку
	index_of_param=-1;
	if (true) {
		for (var i = 0; i < order_dish_ws_parameters_array.length; i++) {

			if (is_isset_in_array_need_to_check==false && order_dish_ws_parameters_array[i].dishId == dishId) {
 				return true;
			}
			else {
				if (is_isset_in_array_need_to_check==true && order_dish_ws_parameters_array[i].dishId == dishId) {
					index_of_param=i;
				   break;
				}
			}
		}
	}
	title = $(".tr_dish_" + dishId + " .column-title").text();
	description = $(".tr_dish_" + dishId + " .column-description").text();
	picture_src = $(".tr_dish_" + dishId + " .column-pictures img").attr('src');
	/*
		function addOneParameterToDishOrder(parameter){
	return "<div class=\"form-group   form-group-param pul-left\">"+

		    "<label for=\"order\" class=\"col-sm-4  control-label- text-left\"><span class=\"param_name\">"+parameter.title+"</span>&nbsp;<span  class=\"param_price\">"+parameter.price+"$</span>&nbsp;<span  class=\"param_weight\">"+parameter.value+parameter.units+"</span></label>"+

			"<div class=\"col-sm-4\">"+
				"<div class=\"input-group input-group-increment\">"+           
					"<div class=\"input-group input-group-param\"><input class=\"increment\" min=\"0\" type=\"number\"  name=\"order\" value=\"1\" class=\"form-control order initialized\" placeholder=\"Input Order\">"+
					"</div>"+
				"</div>"+
			"</div>"+
		"</div>";
		
	*/
	
	
	parameters=[];
	$( ".tr_dish_" + dishId + " .form-group-param" ).each(function( index ) {

		selector_start=".tr_dish_" + dishId + "  .form-group-param:eq( "+index+" )  ";
	//	selector_start2=".tr_dish_" + dishId + "  .param_modal_count:eq( "+index+" )  ";
		selector_start2=".tr_dish_" + dishId + "  .form-group-param:eq( "+index+" )  .param_modal_count";

//increment
//param_modal_count
		console.log(selector_start2);
		console.log($(selector_start2));
			var parameter = 
			{	title:$( selector_start+" input.hidden_param_name  ").val(),
				price:$( selector_start+" input.hidden_param_price").val(),
				value:$( selector_start+" input.hidden_param_value").val(),
				units:$( selector_start+" input.hidden_param_units").val(),
				count:$( selector_start2).val(),
				parameter_id:$( selector_start+" input.hidden_param_id").val(),
				}
				parameters.push(parameter)
			 /*if ($( this ).is(":checked") == true) {
				 $( this ).attr('checked','');
				   // $(this).val('yes');
				} else {

				   // $(this).val('no');
				   $( this ).attr('checked','checked');
				}*/
			
		});
	
	elem={ dishId: dishId, picture:picture_src,title: title, description: description, parameters: parameters };
	//alert(index_of_param);
	console.log(elem);
;	if(index_of_param==-1){
		order_dish_ws_parameters_array.push(elem);
		/*{ dishId: dishId, picture:picture_src,title: title, description: description, parameters: parameters })*/
		//;
		
	}
	else{
		order_dish_ws_parameters_array[index_of_param].parameters=parameters;
	//	var index = order_dish_ws_parameters_array.indexOf(index_of_param);

	//	if (~index) {
	//	    //order_dish_ws_parameters_array[index] = elem;
	//	}
	}
}
/////////////////////////////////////////
///
/////////////////////////////////////////

function removeOrderIfNeedDish(dishId) {
	//flag_contained=false;
	//needRemove=true;
	//если есть то ставим галочку
	for (var i = 0; i < order_dish_ws_parameters_array.length; i++) {

		if (order_dish_ws_parameters_array[i].dishId == dishId) {

			order_dish_ws_parameters_array.splice(i, 1);
			// return true;
			// flag_contained=true;
		}
	}
	//order_dish_ws_parameters_array.push({ dishId: dishId, parameters: [] });
}
/////////////////////////////////////////
///
/////////////////////////////////////////

function orderIsCheckedDishes(dishId) {
	//если есть то ставим галочку

	for (var i = 0; i < order_dish_ws_parameters_array.length; i++) {

		if (order_dish_ws_parameters_array[i].dishId == dishId) {

			return true;
		}
	}
	return false;
}
////////////////////////////
//. add parameter to dish
////////////////////////////
function addOneParameterToDishOrder(parameter,is_modal,dishId){
	var hidden_field="";
	if(is_modal){
		name_parameter="";
		hidden_field="<input type=\"hidden\" class=\"hidden_param_name\" value=\""+parameter.title+"\"/>"+
			"<input type=\"hidden\" class=\"hidden_param_units\" value=\""+parameter.units+"\"/>"+
			"<input type=\"hidden\" class=\"hidden_param_price\" value=\""+parameter.price+"\"/>"+
			"<input type=\"hidden\" class=\"hidden_param_value\" value=\""+parameter.value+"\"/>"+
			//"<input type=\"hidden\" class=\"hidden_param_count\" value=\""+parameter.count+"\"/>"+

			"<input type=\"hidden\" class=\"hidden_param_id\" value=\""+parameter.parameter_id+"\"/>";

	}
	else{
		var c=parameter.count;
		//alert(parameter.title+" "+c);
		if (parameter.count==0){
			return "";
		}
		name_parameter="name=\"parameters["+dishId+"]["+parameter.parameter_id+"][]\"";
		hidden_field="";
			//"<input type=\"hidden\" class=\"hidden_param_id\" value=\""+parameter.parameter_id+"\"/>";
	}
	return "<div class=\"form-group   form-group-param pul-left\">"+
			hidden_field+

		    "<label for=\"order\" class=\"col-sm-4  control-label- text-left\"><span class=\"param_name\">"+parameter.title+"</span>&nbsp;<span  class=\"param_price\">"+parameter.price+"$</span>&nbsp;<span  class=\"param_weight\">"+parameter.value+parameter.units+"</span></label>"+

			"<div class=\"col-sm-4\">"+
				"<div class=\"input-group input-group-increment\">"+           
					"<div class=\"input-group input-group-param\"><input   min=\"0\" type=\"number\"   value=\""+parameter.count+"\" "+name_parameter+" dishid=\""+dishId+"\" class=\"form-control param_modal_count increment initialized\" placeholder=\"0\">"+
					"</div>"+
				"</div>"+
			"</div>"+
		"</div>";
		/*
			"<div class=\"form-group   form-group-param pul-left\">"+

		    "<label for=\"order\" class=\"col-sm-4  control-label- text-left\"><span class=\"param_name\">"+parameter.title+"</span>&nbsp;<span  class=\"param_price\">"+parameter.price+"$</span>&nbsp;<span  class=\"param_weight\">"+parameter.value+parameter.units+"</span></label>"+

			"<div class=\"col-sm-4\">"+
				"<div class=\"input-group input-group-increment\">"+           
					"<div class=\"input-group input-group-param\"><span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary button-minus\">-</button></span><input class=\"increment\" type=\"number\"  name=\"order\" value=\"1\" class=\"form-control order initialized\" placeholder=\"Input Order\"><span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-success button-plus\">+</button></span>"+
					"</div>"+
				"</div>"+
			"</div>"+
		"</div>";
		*/
}


/////////////////////////////////////////
///
/////////////////////////////////////////
function getNewDishesListForOrder(page, startnom) {
	$("#modal_order_dish")
		.html("<div class=\"container container_modal_order_dish\"><div class=\"loader_outer row\"><div class=\"loader center-block\"></div></div></div>");
	$.get(MAIN_API+"dishes_full?page=" + page, function (data) {
		index = startnom;
		$("#modal_order_dish").html("");
		//if(false)

		$.each(data, function (index0, value) {
			checked = "";
			checked_area = "false";

			if (orderIsCheckedDishes(value.id)) {
				checked = "checked";
				checked_area = "true";

			}
			parameters_string="";
			for(i=0;i<value.parameters.length;i++){
				parameters_string+=addOneParameterToDishOrder(value.parameters[i],true,value.id);
			}
			string = "<tr data-key=\"" + value.id + "\" class=\"tr_dish_" + value.id + "\">" +

				"<td class=\"column-\">" +//__modal_selector__
				"<div class=\"icheckbox-helper-modal icheckbox-helper-modal" + value.id + " icheckbox_minimal-blue " + checked + " \" aria-checked=\"" + checked_area + "\" aria-disabled=\"true\"" +
				"style=\"position: relative;\">" +
				"<input type=\"checkbox\" name=\"item\"   class=\"select checkbox\" value=\"" + value.id + "\" style=\"position: absolute; opacity: 0;\">" +
				" </div>" +

				"</td>" +
				"<td class=\"column-id\">" +
				index +
				"</td>" +
				"<td class=\"column-title\">" +
				value.title +
				"</td>" +
				"<td class=\"column-pictures\">"+
				"<img  class=\"img-thumbnail-list-parameter\" src=\""+MAIN_IMAGES+value.thumbnail_small+"\"/>"+
				"</td>" +
				"<td class=\"column-parameter\">"+
				parameters_string +
				"</td>" +
		//		"<td class=\"column-trash\">" +
		//		" <a href=\"javascript:void(0);\" class=\"grid-row\" data-key=\"3\">" +
		//		"<i class=\"fa fa-trash\"></i>" +
		//		"</a>" +
		//		"</td>" +
				"</tr>";
			index++;
			// str = JSON.stringify(obj);
			// console.log(string);
			$("#modal_order_dish")
				.append(string);

		});
		//-----------------add checked event
		orderAddOnModalCheckEventListerToCheckbox();
		orderAddOnIncrementChangeEventLister();
		//orderAddOnButtonPressAddParamEventLister();

	}, "json");

}

/////////////////////////////////////////
///
/////////////////////////////////////////
function getFromFilledDishesListForOrder() {
 
 
	index = 1;//startnom;
	//$("#ready_order_dish").html("");
	//console.log(order_dish_ws_parameters_array);
	$.each(order_dish_ws_parameters_array, function (index0, value) {
		checked = "";
		checked_area = "false";

		/*if (orderIsCheckedDishes(value.dishId)) {
			checked = "checked";
			checked_area = "true";

		}*/
		parameters_string="";

		for(i=0;i<value.parameters.length;i++){
			parameters_string+=addOneParameterToDishOrder(value.parameters[i],false,value.dishId);
		}
		index_for_table_dishes_result++;
		string = "<tr data-key=\"" + value.dishId + "\" class=\"order_dish_item order_dish_item_"+value.dishId+"  order_dish_index_"+index_for_table_dishes_result+"\" >" +

		/*	"<td class=\"column-\">" +//__modal_selector__
			"<div class=\"icheckbox-helper icheckbox-helper" + value.dishId + " icheckbox_minimal-blue " + checked + " \" aria-checked=\"" + checked_area + "\" aria-disabled=\"true\"" +
			"style=\"position: relative;\">" +
			"<input type=\"checkbox\" name=\"item\"   class=\"select checkbox\" value=\"" + value.dishId + "\" style=\"position: absolute; opacity: 0;\">" +
			" </div>" +

			"</td>" +*/
			"<td class=\"column-id\">" +
			index +
			"</td>" +
			"<td class=\"column-title\">" +
			value.title +
			"</td>" +
			"<td class=\"column-pictures\">"+
			"<img  class=\"img-thumbnail-list-parameter\" src=\""+value.picture+"\"/>"+

			"</td>" +
			"<td class=\"column-parameter\">"+
			parameters_string+
			"</td>" +
			"<td class=\"column-remove\">" +
			" <a href=\"javascript:deleteOrderDishItemFromTable("+value.dishId+","+index_for_table_dishes_result+");\" class=\"grid-row-remove \" data-key=\""+value.dishId+"\">" +
			"<i class=\"fa fa-trash\"></i>" +
			"</a>" +
			"</td>" +
			"</tr>";
		index++;
		// str = JSON.stringify(obj);
		// console.log(string);
		$("#ready_order_dish")
			.append(string);

	});
	
	//-----------------now close
			  refreshOrderDishesItemTableNumber();

	//orderAddOnCheckEventListerToCheckbox();
	$('#add-dishes-to-order').modal('hide');
	orderHideShowListOfDishesIsEmpty();
 
}


function orderHideShowListOfDishesIsEmpty(){
	//alert($( "tr.order_dish_item" ).length);
		  if($( "tr.order_dish_item" ).length==0){
	//		  alert("show")
		  $(".box-order-dish-items-empty").show();
	  
	  }
	  else{
		$(".box-order-dish-items-empty").hide();

	  }
}
/////////////////////////////////////////
//// delete item dishfrom ready tables
/////////////////////////////////////////
function deleteOrderDishItemFromTable(dishId,index_of_dishes){
	// $( "tr.order_dish_item.order_dish_item_"+dishId ).remove();
	 // $( "tr.order_dish_item.order_dish_item_"+dishId ).fadeOut(300, function(){ 
	  $( "tr.order_dish_item.order_dish_index_"+index_of_dishes ).fadeOut(300, function(){ 
		  $(this).remove();
		  orderHideShowListOfDishesIsEmpty();
		  refreshOrderDishesItemTableNumber();
	});

	
}
/////////////////////////////////////////
////
/////////////////////////////////////////
function refreshOrderDishesItemTableNumber(){
	
 		
	 $( "tr.order_dish_item .column-id" ).each(function( index ) {
		 cur_ind=index+1;
		 $(this).text(cur_ind);
	});
}
/////////////////////////////////////////
////
/////////////////////////////////////////
function getPaginatonListForOrderModal(page_current, page_count) {
	//<!-- Previous Page Link -->
	//order_dish_modal_current_page_nom=page_current;
	//alert(order_dish_modal_current_page_nom);
	string =
		"<li class=\"page-item disabled\"><span class=\"page-link\">«</span></li>";

	//                           <!-- Pagination Elements -->
	//                           <!-- "Three Dots" Separator -->

	//                           <!-- Array Of Links -->
	for (i = 1; i <= page_count; i++) {
		if (i == page_current) {
			string += "<li class=\"page-item active\"><span class=\"page-link\">" + i + "</span></li>";

		}
		else {
			string += "<li class=\"page-item\"><a class=\"page-link\"  onclick=\"orderDishModalRefreshPage(" + i + ")\">" + i + "</a>" + "</li>";

		}
	}

	//                           <!-- Next Page Link -->
	string += "<li class=\"page-item\"><a class=\"page-link\" href=\"\" rel=\"next\">»</a></li>";

	$("#order_dish_modal_pagination").html(string);

}

//////////////////////////////////////////
////
///////////////////////////////////
function orderDishModalRefreshPage(page) {
	order_dish_modal_current_page_nom = page;
	getNewDishesListForOrder(page, 1);
	getPaginatonListForOrderModal(page, 5)

}
//////////////////////////////////////////
//////icheckbox-helper-modal
///////////////////////////////////

function orderAddOnModalCheckEventListerToCheckbox() {


	$(".icheckbox-helper-modal").click(function () {
		input_elem = $(this).children("input.checkbox").first();
		dishId = input_elem.val();
		if ($(this).hasClass("checked")) {
			$(this).removeClass("checked");
			$(this).attr("aria-checked", "true");
			removeOrderIfNeedDish(dishId);

		}
		else {
			$(this).addClass("checked");
			$(this).attr("aria-checked", "false");
			addOrderIfNeedDish(dishId,false);

		}
		/*$(this ).children( "input.checkbox" ).each(function( index ) {
			 if ($( this ).is(":checked") == true) {
				 $( this ).attr('checked','');
				   // $(this).val('yes');
				} else {

				   // $(this).val('no');
				   $( this ).attr('checked','checked');
				}
			
		});*/
	});

}

//--------------------------
//---
//--------------------------
/*function orderAddOnButtonPressAddParamEventLister() {
	$('.button-plus').on('click', function(event) { //alert("click plus")
	  event.preventDefault(); // To prevent following the link (optional)
	  //alert("s")
	  	elem=$( this ).closest('.input-group-increment').find('input.increment');
	    value=elem.val();
	    value_int=parseInt(value)
	    //alert(value);
		elem.val(value_int+1);
	  
	  
	});

}*/
function orderAddOnIncrementChangeEventLister() {
	$('#modal_order_dish .increment').on('change', function(e){
		 /* console.log(this.value,
		              this.options[this.selectedIndex].value,
		              $(this).find("option:selected").val(),);*/
		 var dishId=$(this).attr('dishid');             
		 addOrderIfNeedDish(dishId,true);             
		});
}
//--------------------------
//---
//--------------------------
/*
function orderAddOnCheckEventListerToCheckbox() {
alert("????")

	$(".icheckbox-helper").click(function () {
		input_elem = $(this).children("input.checkbox").first();
		dishId = input_elem.val();
		if ($(this).hasClass("checked")) {
			$(this).removeClass("checked");
			$(this).attr("aria-checked", "true");
			removeOrderIfNeedDish(dishId);

		}
		else {
			$(this).addClass("checked");
			$(this).attr("aria-checked", "false");
			addOrderIfNeedDish(dishId,false);

		}
		$(this ).children( "input.checkbox" ).each(function( index ) {
			 if ($( this ).is(":checked") == true) {
				 $( this ).attr('checked','');
				   // $(this).val('yes');
				} else {

				   // $(this).val('no');
				   $( this ).attr('checked','checked');
				}
			
		});
	});

}

*/

$(function () {

	$('#add_dishes_to_order').click(function () {
		$('#add-dishes-to-order').modal('show');
		order_dish_modal_current_page_nom = 1;
		//order_dish_ws_parameters_array.clear();
		if(order_dish_ws_parameters_array.length>0) 
		//order_dish_ws_parameters_array.clear();
		order_dish_ws_parameters_array = [];
		orderDishModalRefreshPage(1);
	});
	orderHideShowListOfDishesIsEmpty();



})