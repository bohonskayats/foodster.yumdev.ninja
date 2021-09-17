<?php

namespace App\Admin\Controllers;

use App\Models\PaymentMethod;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentMethodController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PaymentMethod';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentMethod());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
       // $grid->column('parent_id', __('Parent id'));
        $grid->column('sys_name', __('Sys name'));
        $grid->column('order', __('Order'));
        $grid->column('publish')->filter([  0 => 'off',  1 => 'on', ])->bool();

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
        $show = new Show(PaymentMethod::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
       // $show->field('parent_id', __('Parent id'));
        $show->field('sys_name', __('Sys name'));
        $show->field('order', __('Order'));
        $show->field('publish', __('Publish'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PaymentMethod());

        $form->text('title', __('Title'));
        //$form->number('parent_id', __('Parent id'));
        $form->text('sys_name', __('Sys name'));

        $states = [
	        'off' => ['value' => 0, 'text' => 'Not publishing', 'color' => 'danger'],
	        'on'  => ['value' => 1, 'text' => 'Publish', 'color' => 'success'],
		];
 
        $form->switch('publish',__('Publish'))->states($states);
        $form->number('order', __('Order'))->default(1);

        return $form;
    }
}
