<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Parameter;

use App\Models\Dish;
use App\Admin\Selectable\Parameters;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DishController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dish';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Dish());
        
        $grid->column('id', __('Id'))->sortable();
        $grid->column('title', __('Title'))->sortable();
        $grid->column('Category.title', __('Category'));

        $grid->column('base_price', __('Bace price'))->sortable();
        $grid->column('publish')->filter([  0 => 'off',  1 => 'on', ])->bool();
        $grid->column('order', __('Ordering'))->sortable();
        
        
 

        $grid->parameters()->display(function ($parameters) {


		    $parameters2 = array_map(
		    function ($parameter) {
			   // var_dump($parameter);
		        
		        return "<span class='label label-success'>{$parameter['title']}({$parameter['value']}{$parameter['units']})</span>";
		    }, 
		    $parameters);
		
		    return join('&nbsp;', $parameters2);
		});

        
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
        $show = new Show(Dish::findOrFail($id));
        $show->field('title', __('title'));
        $show->field('pictures', __('Thumbnail'))->image();
	   	$show->field('description',__('Description'));
        $show->field('publish', __('Publish'));
        $show->field('order', __('Order'));        
		$show->field('base_price', __('Price'));
        $show->field('category_id', __('Category'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form 
     */
    protected function form()
    {
        $form = new Form(new Dish());
 	   	$form->select("category_id", __('Category'))->options((new Category())::selectOptions());

        $form->text('title', __('Title'));
        $form->image('picture', __('Thumbnail'))->uniqueName();
 	   	$form->currency('base_price');

 		$form->editor('description');
        $states = [
	        'off' => ['value' => 0, 'text' => 'Not publishing', 'color' => 'danger'],
	        'on'  => ['value' => 1, 'text' => 'Publish', 'color' => 'success'],
		];
 
        $form->switch('publish',__('Publish'))->states($states);
        $form->number('order', __('Order'))->default(1);
	//	$form->multipleSelect('parameters','Parameters')->options(Parameter::all()->pluck('title','id'));
		$form->checkbox('parameters','Parameter')->options(Parameter::all()->mapWithKeys(function ($item, $key) {
		    return [$item['id'] => $item['title'].' ('.$item['value'].' '.$item['units'].')'];
		}) );
		//$form->checkbox('parameters','Parameter')->options(Parameter::all()->pluck('title','id'));
		//$form->belongsToMany('parameters', Parameters::class,'Parameter');






        return $form;
    }
}
