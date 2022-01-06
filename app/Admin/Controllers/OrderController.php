<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\City;

use App\Models\DishOrder;
use App\Models\DishParameterOrder;

use App\Models\User;
use App\Models\Address;
use App\Models\PaymentMethod;

use App\Admin\Selectable\Dishes;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
Use Encore\Admin\Admin;
use DB;
use File;

use Illuminate\Database\Eloquent\SoftDeletes;



class OrderController extends AdminController {
	
	
	  
	
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Order';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid() {
		$grid = new Grid(new Order());
				$time = strtotime("now");
		Admin:: js('/admin/javascript/order.js?'.$time);
		Admin:: css('/admin/css/order.css?'.$time);

		
		
		$grid -> column('id', __('Id')) -> sortable();
 
		$grid -> column('total_price', __('Total')) -> sortable() -> editable();
 		/*
			
			 $grid->parameters()->display(function ($parameters) {
		
		
					$parameters2 = array_map(
					function ($parameter) {
 					    
						return "<span class='label label-success'>{$parameter['title']}({$parameter['value']}{$parameter['units']})</span>";
					}, 
					$parameters);
				
					return join('&nbsp;', $parameters2);
				});
		*/

		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id) {
		$show = new Show(Order:: findOrFail($id));

		$show -> field('client_comment', __('client_comment'));
		$show -> field('manager_comment', __('manager_comment'));

		$show -> field('total_price', __('Price'));
		$show -> field('discount_value', __('Price'));
		$show -> field('delivery_price', __('Price'));

		$show -> field('user_id', __('Category'));
		$show -> field('address_id', __('Category'));
		$show -> field('payment_id', __('Category'));


		$show -> field('items_count', __('items_count'));


		$show -> field('day_deliver', __('day_deliver'));
		$show -> field('time_deliver', __('time_deliver'));
		$show -> field('payment_complete', __('payment_complete'));
		$show -> field('delivery_complete', __('delivery_complete'));
		$show -> field('checkout_complete', __('checkout_complete'));



		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	 
	private function html_select($model_field_name,$id,$value,$need_hidden){
		$path = public_path('admin/html');
		$content_select_full = File:: get($path."/new_select.html");
		$content_select_empty = File:: get($path."/new_select_empty.html");
		$content_select_hidden="";

		if($need_hidden==true){
			$content_select_hidden = File:: get($path."/new_select_hidden.html");
			
				
			$content_select_hidden = preg_replace("/##model_field_name##/",
								$model_field_name, 
								$content_select_hidden);
								
			$content_select_hidden = preg_replace("/##_value_##/",
								$value, 
						$content_select_hidden);	
												
			$content_select_hidden = preg_replace("/##_id_##/",
						$id, 
						$content_select_hidden);
			
			
		}
		if($id>0 && strlen($value)>0){
					$content_select=$content_select_full;

		}
		else{
					$content_select=$content_select_empty;

		}
		
		$content_select = preg_replace("/##model_field_name##/",
							$model_field_name, 
							$content_select);
							
		$content_select = preg_replace("/##_value_##/",
							$value, 
					$content_select);	
											
		$content_select = preg_replace("/##_id_##/",
					$id, 
					$content_select);

		return  $content_select.$content_select_hidden;			

	}
	
	private function html_input($model_field_name,$value,$placeholder=""){
		$path = public_path('admin/html');
		$content_input = File:: get($path."/new_input.html");
		
		$content_input = preg_replace("/##model_field_name##/",
							$model_field_name, 
							$content_input);
							
		$content_input = preg_replace("/##_value_##/",
							$value, 
					$content_input);	
																
		$content_input = preg_replace("/##placeholder##/",
					$placeholder, 
					$content_input);
					
		return  $content_input;			

	}
	
	protected function form($order_id) {
		
 		$form = new Form(new Order());
 		
 		$path = public_path('admin/html');

 		$content = File:: get($path."/modal.html");
		$content_table_dishes_p1 = File:: get($path."/table_dishes_p1.html");
		$content_table_dishes_p2 = File:: get($path."/table_dishes_p2.html");
		

		$time = strtotime("now");
		Admin:: js('/admin/javascript/order.js?'.$time);
		Admin:: css('/admin/css/order.css?'.$time);


 		/*	$form->select('user_id',__('User'))->options(function ($id) {
				   $user_id=$id;
				   $user = User::find($id);
		   	
				   if ($user) {
					   return [$user->id => $user->name];
				   }
		   })->ajax('/api/user_list');
		  
		  
		  
			 $form->select('address_id',__('User Adddress'))->options(function ($id) {
				  // $address = Address::find($id);
			   //print($user_id);exit;
				  // if ($address) {
				  //     return [$address->id => $address->title];
				  // }
		   })->ajax('/api/user_address_list/');  
		   */

			//------------------
			//get user info
			//------------------
 			$user = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.id', $order_id)
            ->select('users.id', 'users.email')
            ->first();
			$form->hidden('user_id');
			$user_id=-1;

			if($user==null){
				$form->html(OrderController::html_select('user_id',"","",false),"Phone");

			}
			else{				
				$form->html(OrderController::html_select('user_id',$user->id,$user->email,false),"Phone");
				$user_id=$user->id;
			}
			

			//$form -> html($content_new_user,"Client phone number");
			//if($order_id>0){
			//	 $form -> html("<option value=\"".$user_id."\" selected=\"selected\">".$user_id."</option>");

			//}
		    ///$form -> html("</select");
		  	/*$form -> select('address_id') -> options(function ($id) {
	 			$res= array();
				$adr = Address:: find($id);
	 			//$res[-1] = "help 1 no in db";
				//$res[-2] = "help 2 no in db";
				if ($adr) {
					//if($cur_client_id>0)
					$res2 = Address:: where('user_id', $adr -> user_id) -> get(['id', DB:: raw('title as text')]);
					foreach($res2 as $key=> $r2){
	 					$res[$r2["id"]] = $r2["text"];
					}
	 			}
	 			return $res;
	
			});*/
			
			//------------------
			//get address info !
			//------------------

 			if($user_id>0){
				$address = DB::table('orders')
	            ->join('addresses', 'orders.address_id', '=', 'addresses.id')
	            ->join('cities', 'cities.id', '=', 'addresses.city_id')

	           ->where('orders.id', $order_id)
	            ->where('orders.user_id', $user_id)
	            ->where('addresses.user_id', $user_id)
	
	            ->select('addresses.*','cities.title as city_name')
	            ->first();

			}
			$street_value="";
			$apartment_value="";
			$intercom_value="";
			$floor_value="";
			$city_id="";
			$city_name="";
			$address_id="-1";
			$description="";

			if($address==null || false){
 			}
			else{
				$street_value=$address->street;
				$apartment_value=$address->apartment;
				$intercom_value=$address->intercom;
				$floor_value=$address->floor;


				$city_name=$address->city_name;
				$city_id=$address->city_id;
				$address_id=$address->id;
 				$description=$address->title;
 			}
 
			$form->html(OrderController::html_select('address_id',$address_id,$description,false),"Address");
			$form->hidden("address_id");
	
			$form->html(OrderController::html_select('city_id',$city_id,$city_name,true),"City");
			
			$form->html(OrderController::html_input('street',$street_value),"Street");
			$form->html(OrderController::html_input('apartment',$apartment_value),"Apartment");
			$form->html(OrderController::html_input('intercom',$intercom_value),"Intercom");
			$form->html(OrderController::html_input('floor',$floor_value),"Floor");

			
			


		//$form->select("user_id", __('user_id'))->options((new User())::selectOptions());
		//$form->select("address_id", __('address_id'))->options((new Address())::selectOptions());
		$form -> select("payment_id", __('payment type')) -> options((new PaymentMethod()):: selectOptions());
		$form -> select("status", __('status')) -> options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);

		//$form->editor('description');
		$states = [
			'off' => ['value' => 0, 'text' => 'Not publishing', 'color' => 'danger'],
			'on'  => ['value' => 1, 'text' => 'Publish', 'color' => 'success'],
		];

		//   $form->switch('publish',__('Publish'))->states($states);
		//   $form->number('order', __('Order'))->default(1);

		//$form->belongsToMany('dishes', Dishes::class,'Dish');
		//$modal_view = file_get_contents('../../../../public/admin/html/modal.html');


		
		//$order_dishes=DishOrder:: where('order_id', $order_id) ->get();

		$order_dishes = DB::table('dish_orders')
            ->join('dishes', 'dish_orders.dish_id', '=', 'dishes.id')
            ->join('orders', 'orders.id', '=', 'dish_orders.order_id')
            ->where('orders.id', $order_id)

            ->select('dish_orders.*', 'dishes.picture')
            ->get();
		
		
		$dish_order_ready = array();
		foreach($order_dishes as $item) {

			/*$order_dish_parameters = DB::table('dish_parameter_orders')
			->where('dish_order_id', $item->id)
            ->select('dish_parameter_orders.*')
            ->get();*/
            
			$file_name = File:: name($item->picture);
			$file_extention = File:: extension($item->picture);
			$file_path = File:: dirname($item->picture);
			$thumbnail_small = $file_path.'/'.$file_name."-small.".$file_extention;
			$thumbnail_middle = $file_path.'/'.$file_name."-middle.".$file_extention;



			$dish_order_ready[] = array(
				'id' => $item->id,

				'title' => $item->title,
				'total_price'=> $item->total_price,
				'parameter_description' =>  $item->parameter_description,
				'count'=> $item->count,
				'dish_id'=> $item->dish_id,
				'order_id'=> $item->order_id,
				'picture'=> $item->picture,
				'thumbnail_small'=> $thumbnail_small,

				'description' => $item-> description,
				'base_price' => $item->base_price,
			);

		}
		

		$form -> html($content);
		$form -> html($content_table_dishes_p1);
		$form -> html($content_table_dishes_p2);

		
		//----------------
			$string =" var title = \"\";\n";
			$string.="var description = \"\";\n";
			$string.="var picture_src = \"\";\n";
	
			$string.="var base_price = \"\";\n";
	
			$string.="var count = \"\";\n";
	
			$string.="var parameters = [];\n";
			$string.="var dishId = [];\n";

			Admin::script($string);

		//-----
		foreach($dish_order_ready as $item) {
			//
			$string =" dishId=".$item['dish_id'].";\n";
			$string.=" title = \"".$item['title']."\";\n";
			$string.=" description = \"".$item['description']."\";\n";
	
			$string.=" base_price = \"".$item['base_price']."\";\n";
	
			$string.=" count = \"".$item['count']."\";\n";

			$string.=" picture_src = \"".url("/upload/".$item["thumbnail_small"])."\";\n";


			$string.=" parameters = [];\n";
			
			$order_dish_parameters = DB::table('dish_parameter_orders')
			->where('dish_order_id', $item['id'])
            ->select('dish_parameter_orders.*')
            ->get();
			foreach($order_dish_parameters as $item_parameter) {

				$string.="  parameter = ";
				$string.= "{ \n";
				$string.=	" title: \"".$item_parameter->title."\",\n";
				$string.=	" price: \"".$item_parameter->price."\",\n";
				$string.=	" value: \"".$item_parameter->value."\",\n";
				$string.=	" units: \"".$item_parameter->units."\",\n";
				$string.=	" count: \"".$item_parameter->count."\",\n";
				$string.=	" parameter_id: \"".$item_parameter->parameter_id."\",\n";
				$string.= "}\n";
				$string.=" parameters.push(parameter);\n";
			}
		//});		
			
			$string.=" var elem = { dishId: dishId, count: count, base_price: base_price, picture: picture_src, title: title, description: description, parameters: parameters };\n";
			$string.="order_modal_dish_ws_parameters_array.push(elem);\n";

 			Admin::script($string);
		}
		
		Admin::script("getFromFilledDishesListForOrder() \n");

		



		$form -> currency('total_price') -> disable();
		$form -> currency('discount_value');
		$form -> currency('delivery_price');
		$form -> hidden('items_count') ->default('3');
  	   	
        $form-> textarea("client_comment", __('client_comment')) -> rows(3);
		$form -> textarea("manager_comment", __('manager_comment')) -> rows(3);
		

		$form -> hidden('id');

	//------------------------------------------------------------------------------------------------------
	// callback before save
	//------------------------------------------------------------------------------------------------------
	
	$form -> saving(function (Form $form) {
		//-----------------
		//create user
		//-----------------
		$ready_user_id=	$form ->input("user_id");
		$int_id=intval($ready_user_id);
		if("".$int_id=="".$ready_user_id){
			//ok
		}
		else{
			$int_id=-1;
		}
		$form ->input("user_id",$int_id);
		if($int_id<1){
			//new user
			$user = User::create([
			    'email' => $form ->input("user_id_value"),
 			]);	
			$form ->input("user_id",$user->id);	
			//$ready_user_id=	
		}
		//-----------------
		// end of create user
		//-----------------
		
		
		//-----------------
		//create city
		//-----------------
		$ready_city_id=	$form ->input("city_id");
		$int_id=intval($ready_city_id);
		if("".$int_id=="".$ready_city_id){
			//ok
		}
		else{
			$int_id=-1;
		}
		$form ->input("city_id",$ready_city_id);
		if($int_id<1){
			//new city

			$isssetCity = DB::table('cities')->where("title",$form ->input("city_id_value"))->first();

			if($isssetCity==null){
				$city = City::create([
				    'title' => $form ->input("city_id_value"),
	 			]);		
			}
			else{
				$city=$isssetCity;
			}
			
			
			$form ->input("city_id",$city->id);	
		}
		//-----------------
		// end of create user
		//-----------------
		
		
		//-----------------
		//create address
		//-----------------
		$ready_address_id=	$form ->input("address_id");
		$int_id=intval($ready_address_id);
		if("".$int_id=="".$ready_address_id){
			//ok
		}
		else{
			$int_id=-1;
		}
		$form ->input("address_id",$int_id);

		if($int_id<1){
			//new address
			
			$currentCity = DB::table('cities')
			->where("id",$form ->input("city_id"))
			->select("cities.*")
			->first();
			$intercom_for_save="";
			$floor_for_save="";
			
			if ($form ->input("floor")!=""){
				$floor_for_save=", floor:".$form ->input("floor");
			}
			if ($form ->input("intercom")!=""){
				$intercom_for_save=", intercom:".$form ->input("intercom");
			}
			
			
			$title=$currentCity->title.", ".$form ->input("street")." ".$form ->input("apartment").
			$intercom_for_save.$floor_for_save;

			$address = Address::create([
				'title' => "".$title,

			    'street' => $form ->input("street"),
			    'apartment' => $form ->input("apartment"),
			    'intercom' => $form ->input("intercom"),
			    'floor' => $form ->input("floor"),
			    'city_id' => $form ->input("city_id"),
			    'user_id' => $form ->input("user_id"),
 			]);	

			$form ->input("address_id",$address->id);		
		}
		//-----------------
		// end of create address
		//-----------------		
		
		
	});
	//------------------------------------------------------------------------------------------------------
	// callback after save 
	//------------------------------------------------------------------------------------------------------
	$form -> saved(function (Form $form) {

	$order_id = $form -> model() -> id;



	//--------------------------------
	// save address !!!!
	//--------------------------------
	$query = DB:: table('dish_orders') -> select('id');
	$dish_order_ids = $query -> where('order_id', $order_id) -> get();//addSelect('age')
	//
	$dish_order_ids_ready = array();
	foreach($dish_order_ids as $item) {
		$dish_order_ids_ready[] = $item -> id;
	}
	//
	DishOrder:: where('order_id', $order_id) -> delete ();
	DishParameterOrder:: whereIn('dish_order_id', $dish_order_ids_ready) -> delete ();

	$parameter_dish_order_inputs = $form -> input('parameters');
	$tmp_parameters_info_for_save = [];

	//dishcount. D I S H E S
	$d = $form -> input('dishcount');
	if (isset($d) && count($d) > 0) {
		foreach($form -> input('dishcount') as $dish_id=> $value){
			foreach($value as $dish_number=> $count){

				//------------------------------------------
				// get         P A R A M E T E R S
				//------------------------------------------
 
 				$parameter_text_for_dishes = [];

				if (isset($parameter_dish_order_inputs[$dish_id]) &&
					isset($parameter_dish_order_inputs[$dish_id][$dish_number])) {
					$param_ids_count = $parameter_dish_order_inputs[$dish_id][$dish_number];
					foreach($param_ids_count as $parameter_id => $parameter_count){

						$query = DB:: table('parameters') 
						-> select('title', 'price', 'units', 'value','id');
						$parameter_info = $query -> where('id', $parameter_id) -> first();

						$parameter_text_for_dishes[] = strtolower($parameter_info -> title);
						$tmp_parameters_info_for_save[$parameter_id] = array(
							'count' => $parameter_count,
							'total_price'=> $count,
							'parameter_id'=> $parameter_info ->id,
							//---
							'title'=> $parameter_info -> title,
							'units'=> $parameter_info -> units,
							'price' => $parameter_info -> price,
							'value' => $parameter_info -> value,

						);
					}
 				}//if (isset($parameter_dish_order_inputs[$dish_id]) && ...
				$parameter_description = "";
				if (count($parameter_text_for_dishes) > 0) {
					$parameter_description = "add:".implode(",", $parameter_text_for_dishes);
				}
				//------------------------------------------
				//get          D I S H  I N F O
				//------------------------------------------
				$query = DB:: table('dishes') -> select('title', 'description', 'base_price');
				$dish_info = $query -> where('id', $dish_id) -> first();
				//------------------------------------------
				// add item to dish_orders ...
				$dish_order_id = DB:: table('dish_orders') -> insertGetId(
					[
						'total_price'=> 0,
						'parameter_description' => $parameter_description,
						'count'=> $count,
						'dish_id'=> $dish_id,
						'order_id'=> $order_id,
						//---	         
						'title' => $dish_info -> title,
						'description' => $dish_info -> description,
						'base_price' => $dish_info -> base_price,//$request->input('name'),


					]
				);
				//save parameters
 				if (count($parameter_text_for_dishes) > 0) {
					foreach($tmp_parameters_info_for_save as $parameter_item){
 						// add item to dish_parameter_orders ...
 						$dish_order_parameter_id = DB:: table('dish_parameter_orders') -> insertGetId(
							[
								'dish_order_id'=> $dish_order_id,  
								'count' => $parameter_item["count"], 
								'total_price'=> $parameter_item["total_price"], 
								'parameter_id'=> $parameter_item["parameter_id"], 

								//---
								'title'=> $parameter_item["title"],
								'units'=> $parameter_item["units"],
								'price' => $parameter_item["price"],
								'value' => $parameter_item["value"],

							]
						);

					}
				}//if (count($parameter_text_for_dishes) > 0)
			}//foreach($value as $dish_number=> $count){
		}//foreach($form -> input('dishcount') as $dish_id=> $value){
	}//if (isset($d) && count($d) > 0) {
	
	
	//resave  order ....
	
});

return $form;
    }
  
}
