<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;





class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Category';
//	use ModelForm;

    /*public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Categories');
            $content->body(Category::tree());
        });
    }*/


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        
        
        $grid->column('id', __('Id'))->sortable();
		$grid->column('icon')->image('/upload/', 100, 100);       
		 $grid->column('title', __('Title'))->sortable();
        
        $grid->column('publish')->filter([  0 => 'off',  1 => 'on', ])->bool();
        $grid->column('order', __('Ordering'))->sortable();
        //$grid->column('category_id')->filter([  0 => 'off',  1 => 'on', ])->bool();

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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('title'));
        $show->field('icon', __('Thumbnail'))->image('/upload/', 100, 100);
		//$grid->field('icon')->image('/upload/', 100, 100);       

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
        $form = new Form(new Category());
        $form->text('title', __('Title'));        
		$form->image('icon', trans('admin.icon'))->move('images/')->uniqueName();//admin.avatar

       // $form->select('parent_id', __('Parent Category'))->options((new Category())::selectOptions())->default(0);;
       // $form->select('parent_id', __('Parent Category'))->options((new Category())::selectOptions());

        $states = [
	        'off' => ['value' => 0, 'text' => 'Not publishing', 'color' => 'danger'],
	        'on'  => ['value' => 1, 'text' => 'Publish', 'color' => 'success'],
		];
 
        $form->switch('publish',__('Publish'))->states($states);
        $form->number('order', __('Order'))->default(1);

        return $form;
    }
}
