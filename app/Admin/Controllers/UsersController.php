<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
     //   $grid->column('name', __('Name'));
        $grid->column('email', __('Phone'));
        $grid->column('email_verified_at', __('Email verified at'));
       // $grid->column('password', __('Password'));
        //$grid->column('remember_token', __('Remember token'));
       // $grid->column('fcm_token', __('FCM token'));

/*$grid->column('system')->display(function ($system, $column) {
    
    // If the value of the status field of this column is equal to 1, directly display the title field
    if ($this->system == 1) {
        return "Android";
    }
    else ($this->system == 1) {
        return "iOs";
    }
    else{
	    return "Unknown";
    }
    
    // Otherwise it is displayed as editable
  //  return $column->editable();
});*/
/*$grid->column('system')->label([
    1 => 'default',
    2 => 'warning',
    3 => 'success',
    null => 'info',
]);*/

$grid->column('system')->icon([
    1 => 'android',
    2 => 'apple',
], $default = '');

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
       // $show->field('name', __('Name'));
        $show->field('email', __('Phone'));
        $show->field('email_verified_at', __('Email verified at'));
       // $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('fcm_token', __('FCM token'));
        $show->field('system', __('system'));

        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

       // $form->text('name', __('Name'));
       // $form->email('email', __('Phone'));
        $form->mobile('email', __('Phone'))->options(['mask' => '999 9999 9999']);
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
      //  $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));
        $form->text('fcm_token', __('FCM token'));
        //$form->text('system', __('system'));
		$form->select('system', __('system'))->options([1 => 'Android', 2 => 'Apple']);

        return $form;
    }
}
