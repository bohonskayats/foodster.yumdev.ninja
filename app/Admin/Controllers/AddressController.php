<?php

namespace App\Admin\Controllers;

use App\Models\Address;
use App\Models\User;
use App\Models\City;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AddressController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Address';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Address());
        $grid->column('title', __('Title'))->sortable();
        $grid->column('User.email', __('User'))->sortable();
        $grid->column('City.title', __('City'))->sortable();

        $grid->column('street', __('Street'))->sortable();
        $grid->column('apartment', __('Apartment'))->sortable();
        $grid->column('intercom', __('Intercom'))->sortable();
        $grid->column('floor', __('Floor'))->sortable();


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
        $show = new Show(Address::findOrFail($id));
        $show->field('title', __('title'));
        $show->field('user_id', __('title'));
        $show->field('city_id', __('title'));
        $show->field('street', __('Street'));
        $show->field('apartment', __('Apartment'));
        $show->field('intercom', __('Intercom'));
        $show->field('floor', __('Floor'));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Address());

 	   	$form->select("city_id", __('City'))->options((new City())::selectOptions());
        $form->text('title', __('Title'));
        $form->text('street', __('Street'));
        $form->text('apartment', __('Apartment'));
        $form->text('intercom', __('Intercom'));
        $form->text('floor', __('Floor'));
 	   //	$form->select("user_id", __('User'))->options((new User())::selectOptions());
		$form->select('user_id',__('User'))->options(function ($id) {
		    $user = User::find($id);
		
		    if ($user) {
		        return [$user->id => $user->email];
		    }
		})->ajax('/admin/api_user_list');

        return $form;
    }
}
