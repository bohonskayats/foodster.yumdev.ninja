<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Dish;
 
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

 

        return $form;
    }
}
