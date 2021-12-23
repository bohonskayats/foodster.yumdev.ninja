/////////////////////////////////////////
/// globals
/////////////////////////////////////////
order_dish_modal_current_page_nom=1;
/////////////////////////////////////////
///
/////////////////////////////////////////
function getNewDishesListForOrder(page,startnom){
	 $( "#modal_order_dish" )
		  .html("<div class=\"container container_modal_order_dish\"><div class=\"loader_outer row\"><div class=\"loader center-block\"></div></div></div>");
	$.get( "http://foodster.yumdev.ninja/api/dishes?page="+page, function( data ) {
		index=startnom;
		$( "#modal_order_dish" ).html("");
		//if(false)
		$.each(data, function( index0, value ) {
		  
		  string ="<tr data-key=\"3\">"+
	       
	        "<td class=\"column-__modal_selector__\">"+
	        "<div class=\"icheckbox_minimal-blue\" aria-checked=\"false\" aria-disabled=\"false\""+
	            "style=\"position: relative;\">"+
	            "<input type=\"checkbox\" name=\"item\"  class=\"select\" value=\"3\" style=\"position: absolute; opacity: 0;\">"+
	       " </div>"+
	       
	        "</td>"+
	        "<td class=\"column-id\">"+
	        index+
	        "</td>"+
	        "<td class=\"column-title\">"+
	        value.title+
	        "</td>"+
	        "<td class=\"column-pictures\">"
	        "</td>"+
	        "<td class=\"column-parameter\">"
	        "</td>"+
	        "<td class=\"column-__remove__\">"+
	        " <a href=\"javascript:void(0);\" class=\"grid-row-remove hide\" data-key=\"3\">"+
	        "<i class=\"fa fa-trash\"></i>"+
	        "</a>"+
	        "</td>"+
	        "</tr>";
	        index++;
	     // str = JSON.stringify(obj);
		 // console.log(string);
		  $( "#modal_order_dish" )
		  .append(string);
			  
		});
	//-----------------add pagination
	
	}, "json" );
	
}
/////////////////////////////////////////
////
/////////////////////////////////////////
function getPaginatonListForOrderModal(page_current,page_count){
//<!-- Previous Page Link -->
//order_dish_modal_current_page_nom=page_current;
//alert(order_dish_modal_current_page_nom);
string=
     "<li class=\"page-item disabled\"><span class=\"page-link\">«</span></li>";

	 //                           <!-- Pagination Elements -->
	 //                           <!-- "Three Dots" Separator -->
	
	 //                           <!-- Array Of Links -->
	 for( i=1;i<=page_count;i++){
		 if(i==page_current){
 		 	string+="<li class=\"page-item active\"><span class=\"page-link\">"+i+"</span></li>";
			 
		 }
		 else{
			string+=  "<li class=\"page-item\"><a class=\"page-link\"  onclick=\"orderDishModalRefreshPage("+i+")\">"+i+"</a>"+"</li>";
	
		 }
	 }
	
	 //                           <!-- Next Page Link -->
	string+="<li class=\"page-item\"><a class=\"page-link\" href=\"\" rel=\"next\">»</a></li>";
	
	$("#order_dish_modal_pagination").html(string);	

}

//////////////////////////////////////////
////
///////////////////////////////////
function orderDishModalRefreshPage (page)
{
	order_dish_modal_current_page_nom=page;
	getNewDishesListForOrder(page,1); 
	getPaginatonListForOrderModal(page,5)	

}

$(function() {
 
$('#add_dishes_to_order').click(function(){

    $('#exampleModalLong').modal('show') ;
    orderDishModalRefreshPage (1);
});
	



})