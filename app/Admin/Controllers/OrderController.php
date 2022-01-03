<?php

namespace App\Admin\Controllers;

use App\Models\Order;
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

// print_dish_order($item){
	/*  $string='';
	  $string+=" var title = \"".$item['title']."\";\n";
	  $string+="var description = \"".$item['description']."\";\n";
	  $string+="var picture_src = \"".$item['title']."\";\n";

	  $string+="var base_price = \"".$item['base_price']."\";\n";

	  $string+="var count = \"".$item['count']."\";\n";

	  $string +="var parameters = [];\n";
	  */
	 /* $(".tr_dish_" + dishId + " .form-group-param").each(function (index) {

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

	});*/

	//$string="var elem = { dishId: dishId, count: count, base_price: base_price, picture: picture_src, title: title, description: description, parameters: parameters };";
	//$string+="order_modal_dish_ws_parameters_array.push(elem);\n";
	  
	// return $string;   
	   
	    
   // }


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
		$grid -> column('id', __('Id')) -> sortable();
		//$grid->column('title', __('Title'))->sortable();
		//  $grid->column('Category.title', __('Category'));

		$grid -> column('total_price', __('Total')) -> sortable() -> editable();
		///   $grid->column('publish')->filter([  0 => 'off',  1 => 'on', ])->bool();
		//    $grid->column('order', __('Ordering'))->sortable();

		/*
			
			 $grid->parameters()->display(function ($parameters) {
		
		
					$parameters2 = array_map(
					function ($parameter) {
					   // var_dump($parameter);
					    
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

		//   $show->field('order', __('Order'));        
		//   $show->field('publish', __('publish'));        


		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form($order_id) {
		//var_dump($order_id);
 		$form = new Form(new Order());
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


		$form -> select('user_id') -> options(function ($id) {
			$user = User:: find($id);
			if ($user) {
				return [$user -> id => $user -> name];
			}
		}) -> ajax('/api/user_list/') -> load('address_id', '/api/user_address_list_by/');

		$form -> select('address_id') -> options(function ($id) {
			$adr = Address:: find($id);
			$res[-1] = "sdfsd-1fsd";
			$res[-2] = "sdf33333sd-1fsd";
			if ($adr) {
				$res2 = Address:: where('user_id', $adr -> user_id) -> get(['id', DB:: raw('title as text')]);
				foreach($res2 as $key=> $r2){
 					$res[$r2["id"]] = $r2["text"];
				}
 			}
 			return $res;

		});
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

		$path = public_path('admin/html');

		$content = File:: get($path."/modal.html");
		$content_table_dishes_p1 = File:: get($path."/table_dishes_p1.html");
		
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
			/*foreach($order_dish_parameters as $item_parameter){
				$dish_parameter_order_ready[] = array(
					
					'dish_order_id'=> $item->dish_order_id,

					'total_price'=> $item->total_price,
					'count'=> $item->count,
					'title' => $item->title,
					'units' =>  $item->units,
					'price'=> $item->price,
					'value'=> $item->value,
				);
			}*/

		}
		
		$content_table_dishes_p2 = File:: get($path."/table_dishes_p2.html");

		$form -> html($content);
		$form -> html($content_table_dishes_p1);
		$form -> html($content_table_dishes_p2);

		$time = strtotime("now");
		Admin:: js('/admin/javascript/order.js?'.$time);
		Admin:: css('/admin/css/order.css?'.$time);
		
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
				/*$dish_parameter_order_ready[] = array(
									
									'dish_order_id'=> $item->dish_order_id,
				
									'total_price'=> $item->total_price,
									'count'=> $item->count,
									'title' => $item->title,
									'units' =>  $item->units,
									'price'=> $item->price,
									'value'=> $item->value,
								);*/
	
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
		




		$form -> currency('total_price') -> disable();
		$form -> currency('discount_value');
		$form -> currency('delivery_price');
		$form -> hidden('items_count') ->default('3');
  	   	
        $form-> textarea("client_comment", __('client_comment')) -> rows(3);
		$form -> textarea("manager_comment", __('manager_comment')) -> rows(3);
		
		//	$form->multipleSelect('parameters','Parameters')->options(Parameter::all()->pluck('title','id'));
		/*$form->checkbox('parameters','Parameter')->options(Parameter::all()->mapWithKeys(function ($item, $key) {
			return [$item['id'] => $item['title'].' ('.$item['value'].' '.$item['units'].')'];
		}) );*/
		//$form->checkbox('parameters','Parameter')->options(Parameter::all()->pluck('title','id'));
		//$form->belongsToMany('parameters', Parameters::class,'Parameter');
		//$order_id = $form -> id;
		//var_dump($form -> model() );
		//var_dump($order_id);
		//var_dump($form->id);
		//$form->display('id', 'ID');
		//var_dump($form -> hidden('id')) ;
		$form -> hidden('id');
	
		
		
 		// callback before save
		$form -> saving(function (Form $form) {
			//...
		
		
		});
	//----------------------------------
	// callback after save
	//----------------------------------
	$form -> saved(function (Form $form) {

	$order_id = $form -> model() -> id;

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
//var_dump($tmp_parameters_info_for_save);exit;
				}
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
				//var_dump($parameter_text_for_dishes);exit;
				if (count($parameter_text_for_dishes) > 0) {
					foreach($tmp_parameters_info_for_save as $parameter_item){
						//var_dump($parameter_item);
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
					//exit;



				}
			}
		}
	}
	/*$id = DB:: table('dish_orders') -> insertGetId(
		[
			'total_price'=> 0,
			'parameter_description' => 'parameter_description',
			'count'=> 0,
			'dish_id'=> 0,
			'order_id'=> 0,
			//---	         
			'title' => 'title',
			'description' => 'description',
			'base_price' => 0,//$request->input('name'),


		]
	);*/
	/*exit;
	//var_dump($form->input('dishcount'));exit;
	
	$id = DB:: table('dish_parameter_orders') -> insertGetId(
		['dish_order_id' => 0,//$request->input('name'),
			'count'=> 0,//$request->input('address')]
			'total_price'=> 0,
			'title'=> '',
			'units'=> '',
			'price'=> 0,
			'value'=> 0]
	);*/
	//---------------
	/*
	 $table->decimal('total_price', $precision = 8, $scale = 2);
	$table->text('parameter_description');
	$table->integer('count') ;
	$table->integer('dish_id') ;
	$table->integer('order_id');
	//---
	$table->string('title');
	$table->text('description');
	$table->decimal('base_price', $precision = 8, $scale = 2);

	*/
	/*	$id = DB:: table('dish_orders') -> insertGetId(
			[
				'total_price'=> 0,
				'parameter_description' => 'parameter_description',
				'count'=> 0,
				'dish_id'=> 0,
				'order_id'=> 0,
				//---	         
				'title' => 'title',
				'description' => 'description',
				'base_price' => 0,//$request->input('name'),
	
	
			]
		);
	*/
});

return $form;
    }
  
}
