<?php

namespace App\Admin\Controllers;

use App\Models\Parameter;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ParameterController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Parameter';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Parameter());
        $grid->column('id', __('Id'))->sortable();
        $grid->column('title', __('Title'))->sortable();
        $grid->column('value', __('Value'))->sortable();
        $grid->column('units', __('Units'))->sortable();

        $grid->column('price', __('Price'))->sortable();
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
        $show = new Show(Parameter::findOrFail($id));
        $show->field('title', __('title'));
		$show->field('value', __('Value'));
		$show->field('units', __('Units'));
		$show->field('price', __('Price'));

        $show->field('publish', __('Publish'));
        $show->field('order', __('Order'));    
            



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Parameter());

 
        $form->text('title', __('Title'));
        $form->text('value', __('Value'));
        $form->text('units', __('Units'));

 	   	$form->currency('price');

        $states = [
	        'off' => ['value' => 0, 'text' => 'Not publishing', 'color' => 'danger'],
	        'on'  => ['value' => 1, 'text' => 'Publish', 'color' => 'success'],
		];
 
        $form->switch('publish',__('Publish'))->states($states);
        $form->number('order', __('Order'))->default(1);


        return $form;
    }
}
