//----------------------------------------------------------------------------------------------------------------------------------------
////   G L O B A L S
//----------------------------------------------------------------------------------------------------------------------------------------
MAIN_SITE = "http://foodster.yumdev.ninja";
MAIN_API = MAIN_SITE + "/api/";
MAIN_IMAGES = MAIN_SITE + "/upload/";
index_for_table_dishes_result = 1000;
order_dish_modal_current_page_nom = 1;
var order_modal_dish_ws_parameters_array = [];
var order_dish_ws_parameters_array = [];

//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------

function addOrderIfNeedDish(dishId) {
	//is_isset_in_array_need_to_check
	var is_checked;
	if ($(".icheckbox-helper-modal" + dishId+".checked").length>0) {
		
		
		is_checked = true;
	}
	else {
		is_checked = false;
		//return -1;

	}
	var index_of_dish = -1;
	if (is_checked) {
		for (var i = 0; i < order_modal_dish_ws_parameters_array.length; i++) {

			if ( order_modal_dish_ws_parameters_array[i].dishId == dishId) {
				index_of_dish = i;
				break;
			}
		}
	}
	var title = $(".tr_dish_" + dishId + " .column-title").text();
	var description = $(".tr_dish_" + dishId + " .column-description").text();
	var picture_src = $(".tr_dish_" + dishId + " .column-pictures img").attr('src');
	var base_price = $(".tr_dish_" + dishId + " .column-price  div span.price").text();
	var count = $(".tr_dish_" + dishId + " td.column-price .dish_modal_count").val();

	var parameters = [];
	$(".tr_dish_" + dishId + " .form-group-param").each(function (index) {

		var selector_start = ".tr_dish_" + dishId + "  .form-group-param:eq( " + index + " )  ";
		var selector_start2 = ".tr_dish_" + dishId + "  .form-group-param:eq( " + index + " )  .param_modal_count";

		var parameter =
		{
			title: $(selector_start + " input.hidden_param_name  ").val(),
			price: $(selector_start + " input.hidden_param_price").val(),
			value: $(selector_start + " input.hidden_param_value").val(),
			units: $(selector_start + " input.hidden_param_units").val(),
			count: $(selector_start2).val(),
			parameter_id: $(selector_start + " input.hidden_param_id").val(),
		}
		parameters.push(parameter)

	});

	var elem = { dishId: dishId, count: count, base_price: base_price, picture: picture_src, title: title, description: description, parameters: parameters };
	if (is_checked) {
		if (index_of_dish == -1) {
			order_modal_dish_ws_parameters_array.push(elem);
		}
		else {
			order_modal_dish_ws_parameters_array[index_of_dish].parameters = parameters;
			order_modal_dish_ws_parameters_array[index_of_dish].base_price = base_price;
			order_modal_dish_ws_parameters_array[index_of_dish].picture = picture_src;
			order_modal_dish_ws_parameters_array[index_of_dish].title = title;
			order_modal_dish_ws_parameters_array[index_of_dish].count = count;
		}

	}
 
	var total_item_price = totalOrderDishByDataItem(elem.parameters, elem.base_price, elem.count);
	$(".tr_dish_" + dishId + " .column-total span.total").text(total_item_price);
		console.log(order_modal_dish_ws_parameters_array);

	//return total_item_price;
}
//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------

function removeOrderIfNeedDish(dishId) {
	//flag_contained=false;
	//needRemove=true;
	//если есть то ставим галочку
	for (var i = 0; i < order_modal_dish_ws_parameters_array.length; i++) {

		if (order_modal_dish_ws_parameters_array[i].dishId == dishId) {

			order_modal_dish_ws_parameters_array.splice(i, 1);
			// return true;
			// flag_contained=true;
		}
	}
	//order_modal_dish_ws_parameters_array.push({ dishId: dishId, parameters: [] });
}
//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------

function orderIsCheckedDishes(dishId) {
	//если есть то ставим галочку

	for (var i = 0; i < order_modal_dish_ws_parameters_array.length; i++) {

		if (order_modal_dish_ws_parameters_array[i].dishId == dishId) {

			return true;
		}
	}
	return false;
}
////////////////////////////
//. add parameter to dish
////////////////////////////
function addOneParameterToDishOrder(parameter, is_modal, dishId,table_dish_index) {
	var name_parameter=""
	var hidden_field = "";
	if (is_modal) {
		//name_parameter = "";
		hidden_field = "<input type=\"hidden\" class=\"hidden_param_name\" value=\"" + parameter.title + "\"/>" +
			"<input type=\"hidden\" class=\"hidden_param_units\" value=\"" + parameter.units + "\"/>" +
			"<input type=\"hidden\" class=\"hidden_param_price\" value=\"" + parameter.price + "\"/>" +
			"<input type=\"hidden\" class=\"hidden_param_value\" value=\"" + parameter.value + "\"/>" +
			//"<input type=\"hidden\" class=\"hidden_param_count\" value=\""+parameter.count+"\"/>"+

			"<input type=\"hidden\" class=\"hidden_param_id\" value=\"" + parameter.parameter_id + "\"/>";
			//name_parameter="";
	}
	else {
		var c = parameter.count;
		if (parameter.count == 0) {
			return "";
		}
		name_parameter = "name=\"parameters[" + dishId + "]["+table_dish_index+"][" + parameter.parameter_id + "]\"";
		hidden_field = "";
		//"<input type=\"hidden\" class=\"hidden_param_id\" value=\""+parameter.parameter_id+"\"/>";
	}
	return "<div class=\"form-group   form-group-param pul-left\">" +
		hidden_field +

		"<label for=\"order\" class=\"col-sm-8  control-label- text-left\"><span class=\"param_name\">" + parameter.title + "</span>&nbsp;<span  class=\"param_price\">" + parameter.price + "$</span>&nbsp;<span  class=\"param_weight\">" + parameter.value + parameter.units + "</span></label>" +

		"<div class=\"col-sm-4\">" +
		"<div class=\"input-group input-group-increment\">" +
		"<div class=\"input-group input-group-param\"><input   min=\"0\" type=\"number\"   value=\"" + parameter.count + "\" " +
		 name_parameter + 
		" dishid=\"" + dishId + "\" class=\"form-control param_modal_count increment initialized\" placeholder=\"0\">" +
		"</div>" +
		"</div>" +
		"</div>" +
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
//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------
function totalOrderDishItem(dishId, isModal) {

	return 0;
}
/////
function totalOrderDishByDataItem(parameters, base_price, count) {
	add = 0;
	for (i = 0; i < parameters.length; i++) {
		add += parseInt(parameters[i].count) * parseFloat(parameters[i].price);
	}
	return base_price * count + add;
	return 0;
}

//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------
function getNewDishesListForOrder(page, startnom) {
	$("#modal_order_dish")
		.html("<div class=\"container container_modal_order_dish\"><div class=\"loader_outer row\"><div class=\"loader center-block\"></div></div></div>");
	$.get(MAIN_API + "dishes_full?page=" + page, function (data) {
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
			parameters_string = "";
			var name_count_name = "";//"name=\"dishcount[" + value.id + "][]\"";

			for (i = 0; i < value.parameters.length; i++) {
				parameters_string += addOneParameterToDishOrder(value.parameters[i], true, value.id,-1);
			}

			string = "<tr data-key=\"" + value.id + "\" class=\"tr_dish_" + value.id + "\">" +

				"<td class=\"column-\">" +//__modal_selector__
				"<div class=\"icheckbox-helper-modal  icheckbox-helper-modal" + value.id + " icheckbox_minimal-blue " + checked + " \" aria-checked=\"" + checked_area + "\" aria-disabled=\"true\"" +
				"style=\"position: relative;\">" +
				"<input type=\"checkbox\" name=\"item\"   class=\"select checkbox checkbox-add-dish" + value.id + "\" value=\"" + value.id + "\" style=\"position: absolute; opacity: 0;\">" +
				" </div>" +

				"</td>" +
				"<td class=\"column-id\">" +
				index +
				"</td>" +
				"<td class=\"column-title\">" +
				value.title +
				"</td>" +
				"<td class=\"column-pictures\">" +
				"<img  class=\"img-thumbnail-list-parameter\" src=\"" + MAIN_IMAGES + value.thumbnail_small + "\"/>" +
				"</td>" +
				"<td class=\"column-parameter\">" +
				parameters_string +
				"</td>" +
				"<td class=\"column-price\"><div class=\"col-sm-6 text-right\"><span class=\"price\">" +
				value.base_price +
				"</span><span>$</span></div>" +

				"<span class=\"dish_count col-sm-2\">x</span>" +
				"<span class=\"dish_count col-sm-3\"><input   min=\"1\" type=\"number\"   value=\"" + value.count + "\"" + name_count_name +
				" dishid=\"" + value.id + "\" class=\"form-control dish_modal_count increment initialized\" placeholder=\"0\">" +
				"</span></td>" +
				"<td class=\"column-total\"><span class=\"total\">" +
				totalOrderDishByDataItem(value.parameters, value.base_price, value.count) +
				"</span><span>$</span></td>" +

				//		"<td class=\"column-trash\">" +
				//		" <a href=\"javascript:void(0);\" class=\"grid-row\" data-key=\"3\">" +
				//		"<i class=\"fa fa-trash\"></i>" +
				//		"</a>" +
				//		"</td>" +
				"</tr>";
			/*
				
				<div class=\"form-group   form-group-param pul-left\">"+
		hidden_field+

		"<label for=\"order\" class=\"col-sm-4  control-label- text-left\"><span class=\"param_name\">"+parameter.title+"</span>&nbsp;<span  class=\"param_price\">"+parameter.price+"$</span>&nbsp;<span  class=\"param_weight\">"+parameter.value+parameter.units+"</span></label>"+

		"<div class=\"col-sm-4\">"
				
			*/
			index++;
			$("#modal_order_dish")
				.append(string);

		});
		//-----------------add checked event
		orderAddOnModalCheckEventListerToCheckbox();
		orderAddOnIncrementChangeEventLister();
		//orderAddOnButtonPressAddParamEventLister();

	}, "json");

}



//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------
function calculateOrderTotalDishesPrice() {

	total_dishes_price = 0;
	$.each(order_dish_ws_parameters_array, function (index0, value) {

		total_item = totalOrderDishByDataItem(value.parameters, value.base_price, value.count);
		total_dishes_price += total_item;
	});
	return total_dishes_price;
}

function calculateOrderTotalPrice(total_dishes_price) {
 	
	var amount_delivery = parseFloat($("#delivery_price").val());
	var amount_discount = parseFloat($("#discount_value").val());

	return total_dishes_price + amount_delivery - amount_discount;
}
//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------
function getFromFilledDishesListForOrder() {
 
	var index = 1;//startnom;
	var total_dishes_price = 0;

	$.each(order_modal_dish_ws_parameters_array, function (index0, value) {
 		//checked = "";
		//checked_area = "false";

		//order_dish_ws_parameters_array.push(value);
		//console.log(value);
		var parameters_string = "";
		index_for_table_dishes_result++;
		var name_count_name = "name=\"dishcount[" + value.dishId + "]["+index_for_table_dishes_result+"]\"";

		for (i = 0; i < value.parameters.length; i++) {
			parameters_string += addOneParameterToDishOrder(value.parameters[i], false, value.dishId,index_for_table_dishes_result);
		}
		var total_item = totalOrderDishByDataItem(value.parameters, value.base_price, value.count);
		//total_dishes_price += total_item;
		var string = "<tr data-key=\"" + value.dishId + "\" class=\"order_dish_item order_dish_item_" + value.dishId + "  order_dish_index_" + index_for_table_dishes_result + "\" >" +

			"<td class=\"column-id\">" +
			index +
			"</td>" +
			"<td class=\"column-title\">" +
			value.title +
			"</td>" +
			"<td class=\"column-pictures\">" +
			"<img  class=\"img-thumbnail-list-parameter\" src=\"" + value.picture + "\"/>" +

			"</td>" +
			"<td class=\"column-parameter\">" +
			parameters_string +
			"</td>" +

			"<td class=\"column-price\"><div  class=\"price col-sm-6 text-right\"><span class=\"price\">" +
			value.base_price +
			"</span><span>$</span></div>" +
			"<span class=\"dish_count col-sm-2\">x</span>" +
			"<span class=\"dish_count col-sm-4\"><input   min=\"0\" type=\"number\"   value=\"" + value.count + "\" " + name_count_name +
			" dishid=\"" + value.dishId + "\" class=\"form-control param_modal_count increment initialized\" placeholder=\"0\">" +
			"</span></td>" +

			"<td class=\"column-total\"><span class=\"total\">" +
			total_item +
			//totalOrderDishByDataItem(value.parameters,value.base_price,value.count) +
			"</span><span>$</span></td>" +

			"<td class=\"column-remove\">" +
			" <a href=\"javascript:deleteOrderDishItemFromTable(" + value.dishId + "," + index_for_table_dishes_result + ");\" class=\"grid-row-remove \" data-key=\"" + value.dishId + "\">" +
			"<i class=\"fa fa-trash\"></i>" +
			"</a>" +
			"</td>" +
			"</tr>";
		index++;
		value.index_of_table = index_for_table_dishes_result;
		order_dish_ws_parameters_array.push(value);
		$("#ready_order_dish")
			.append(string);

	});

	//-----------------now close

	//total_dishes_price
	refreshOrderDishesItemTableNumber();
	//setOrderDishesTotalPrice(total_dishes_price);
	//orderAddOnCheckEventListerToCheckbox();
	$('#add-dishes-to-order').modal('hide');
	orderHideShowListOfDishesIsEmpty();
	refreshOrdersTotals()
}
//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------

function refreshOrdersTotals() {

	var total_dishes_price = 0;
	$.each(order_dish_ws_parameters_array, function (index0, value) {

		var total_item = totalOrderDishByDataItem(value.parameters, value.base_price, value.count);
		total_dishes_price += total_item;
	});

 	setOrderDishesTotalPrice(total_dishes_price);
	var total_price=calculateOrderTotalPrice(total_dishes_price);
 	setOrderTotalPrice(total_price);
	orderHideShowListOfDishesIsEmpty();
}

//----------------------------------------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------------------------------------

function setOrderDishesTotalPrice(total_dishes_price) {
	$(".box-order-dish-items-total-price").text(total_dishes_price);
}
function setOrderTotalPrice(total_price) {
	$("#total_price").val(total_price);
}
function orderHideShowListOfDishesIsEmpty() {

	if ($("tr.order_dish_item").length == 0) {
		$(".box-order-dish-items-total-price").hide();
				$(".box-order-dish-items-empty").show();

	}
	else {
		$(".box-order-dish-items-empty").hide();
		$(".box-order-dish-items-total-price").show();
	}
}
//----------------------------------------------------------------------------------------------------------------------------------------
//// delete item dishfrom ready tables
//----------------------------------------------------------------------------------------------------------------------------------------
function deleteOrderDishItemFromTable(dishId, index_of_dishes) {
	// $( "tr.order_dish_item.order_dish_item_"+dishId ).remove();
	// $( "tr.order_dish_item.order_dish_item_"+dishId ).fadeOut(300, function(){ 
	$("tr.order_dish_item.order_dish_index_" + index_of_dishes).fadeOut(300, function () {
		$(this).remove();
		orderHideShowListOfDishesIsEmpty();
		refreshOrderDishesItemTableNumber();
		remove_from_dishes_list_array(index_of_dishes);
		refreshOrdersTotals();

	});


}
//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------
function remove_from_dishes_list_array(index_of_dishes) {

	var sum_index = -1;
	var length = order_dish_ws_parameters_array.length;
	for (var index0 = 0; index0 < length; index0++) {
		sum_index++;
		if (order_dish_ws_parameters_array[index0].index_of_table == index_of_dishes) {
			order_dish_ws_parameters_array.splice(index0, 1);
			length--;
			index0--;
		}

		if (sum_index == 20) break;
	}


}
//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------
function refreshOrderDishesItemTableNumber() {


	$("tr.order_dish_item .column-id").each(function (index) {
		cur_ind = index + 1;
		$(this).text(cur_ind);
	});
}
//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------
function getPaginatonListForOrderModal(page_current, page_count) {
	//<!-- Previous Page Link -->
	//order_dish_modal_current_page_nom=page_current;
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

//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------
function orderDishModalRefreshPage(page) {
	order_dish_modal_current_page_nom = page;
	getNewDishesListForOrder(page, 1);
	getPaginatonListForOrderModal(page, 5)

}
//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------

function orderAddOnModalCheckEventListerToCheckbox() {


	$(".icheckbox-helper-modal").click(function () {
		input_elem = $(this).children("input.checkbox").first();
		dishId = input_elem.val();
		if ($(this).hasClass("checked")) {
			
			$(this).removeClass("checked");
			$(this).attr("aria-checked", "false");
			removeOrderIfNeedDish(dishId);

		}
		else {
		
			$(this).addClass("checked");
			$(this).attr("aria-checked", "true");
			addOrderIfNeedDish(dishId, false);


		}


	});

}

//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------
/*function orderAddOnButtonPressAddParamEventLister() {
	$('.button-plus').on('click', function(event) { 
	  event.preventDefault(); // To prevent following the link (optional)
			elem=$( this ).closest('.input-group-increment').find('input.increment');
		value=elem.val();
		value_int=parseInt(value)
			elem.val(value_int+1);
	  
	  
	});

}*/
function orderAddOnIncrementChangeEventLister() {
	$('#modal_order_dish .increment').on('change', function (e) {

		var dishId = $(this).attr('dishid');
		addOrderIfNeedDish(dishId, true);

	});
}
//----------------------------------------------------------------------------------------------------------------------------------------
////  
//----------------------------------------------------------------------------------------------------------------------------------------
/*
function orderAddOnCheckEventListerToCheckbox() {
 
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
		//order_modal_dish_ws_parameters_array.clear();
		if (order_modal_dish_ws_parameters_array.length > 0)
			//order_modal_dish_ws_parameters_array.clear();
			order_modal_dish_ws_parameters_array = [];
		orderDishModalRefreshPage(1);
	});
	
	
	orderHideShowListOfDishesIsEmpty();



})