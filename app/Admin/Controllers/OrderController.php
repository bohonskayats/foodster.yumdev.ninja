<?php

namespace App\Admin\Controllers;

use App\Models\Order;
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

class OrderController extends AdminController
{
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
    protected function grid()
    {
        $grid = new Grid(new Order());
        $grid->column('id', __('Id'))->sortable();
        //$grid->column('title', __('Title'))->sortable();
      //  $grid->column('Category.title', __('Category'));

        $grid->column('total_price', __('Total'))->sortable()->editable();
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
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

  	   	$show->field('client_comment',__('client_comment'));
        $show->field('manager_comment', __('manager_comment'));
        
		$show->field('total_price', __('Price'));
 		$show->field('discount_value', __('Price'));
		$show->field('delivery_price', __('Price'));

        $show->field('user_id', __('Category'));
        $show->field('address_id', __('Category'));
        $show->field('payment_id', __('Category'));


        $show->field('items_count', __('items_count'));  
        
              
        $show->field('day_deliver', __('day_deliver'));        
        $show->field('time_deliver', __('time_deliver'));        
        $show->field('payment_complete', __('payment_complete'));        
        $show->field('delivery_complete', __('delivery_complete'));        
        $show->field('checkout_complete', __('checkout_complete'));        

     //   $show->field('order', __('Order'));        
     //   $show->field('publish', __('publish'));        


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
	    Admin::js('/admin/javascript/order.js');
	    Admin::script('console.log("hello world");');
        $form = new Form(new Order());
     //   var_dump($id);
     //   exit;
		//$user_id="0";
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
		
 
		$form->select('user_id')->options(function ($id) {
			    			    $user = User::find($id);
			
			    if ($user) {
			        return [$user->id => $user->name];
			    }
		})->ajax('/api/user_list/')->load('address_id', '/api/user_address_list_by/');

		$form->select('address_id')->options(function ($id) {
			  $adr = Address::find($id);
 			$res[-1]="sdfsd-1fsd";
 			$res[-2]="sdf33333sd-1fsd";
			//$res[] =$r ;

			    if ($adr) {
				    $res2=Address::where('user_id', $adr->user_id)->get(['id', DB::raw('title as text')]);
					foreach($res2 as $key=>$r2){
 						//exit;
						$res[$r2["id"]] =$r2["text"] ;
					}
					//var_dump([$adr->id => $adr->title."!!!!!".$adr->user_id]);				    
			        //return [$adr->id => $adr->title."!!!!!".$adr->user_id];
			    }
 			 // exit;
			   return $res;

		});
		
		/*$form->divider();

		        $form->text('address_title', __('Title'));
        $form->text('address_street', __('Street'));
        $form->text('address_apartment', __('Apartment'));
        $form->text('address_intercom', __('Intercom'));
        $form->text('address_floor', __('Floor'));

		$form->divider();

		*/
 	   //	$form->select("user_id", __('user_id'))->options((new User())::selectOptions());
 	  // 	$form->select("address_id", __('address_id'))->options((new Address())::selectOptions());
 	 	$form->select("payment_id", __('payment type'))->options((new PaymentMethod())::selectOptions());
 	 	$form->select("status", __('status'))->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);

 		//$form->editor('description');
        $states = [
	        'off' => ['value' => 0, 'text' => 'Not publishing', 'color' => 'danger'],
	        'on'  => ['value' => 1, 'text' => 'Publish', 'color' => 'success'],
		];
 
     //   $form->switch('publish',__('Publish'))->states($states);
     //   $form->number('order', __('Order'))->default(1);
        $form->belongsToMany('dishes', Dishes::class,'Dish');


  	  	$form->currency('total_price');
  	   	$form->currency('discount_value');
  	   	$form->currency('delivery_price');
  	   	$form->number('items_count')->default('3');
  	   	
        $form->textarea("client_comment",__('client_comment'))->rows(3);
        $form->textarea("manager_comment",__('manager_comment'))->rows(3);

	//	$form->multipleSelect('parameters','Parameters')->options(Parameter::all()->pluck('title','id'));
		/*$form->checkbox('parameters','Parameter')->options(Parameter::all()->mapWithKeys(function ($item, $key) {
		    return [$item['id'] => $item['title'].' ('.$item['value'].' '.$item['units'].')'];
		}) );*/
		//$form->checkbox('parameters','Parameter')->options(Parameter::all()->pluck('title','id'));
		//$form->belongsToMany('parameters', Parameters::class,'Parameter');


        return $form;
    }
}
