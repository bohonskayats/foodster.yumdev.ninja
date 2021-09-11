<?php

namespace App\Admin\Controllers;

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
        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('baseprice', __('Bace price'));

        $grid->column('description', __('Description'))->style('max-width:200px;word-break:break-all;')->display(function ($val){
            return substr($val,0,30);
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
        //$show->field('category_id', __('Parameter_id'));
       $show->field('pictures', __('Thumbnail'))->image();
	   	$show->field('description',__('Description'));

       // $show->column('Cagegory.title', __('Category'));
       /* $show->column('description', __('Description'))->style('max-width:200px;word-break:break-all;')->display(function ($val){
            return substr($val,0,30);
      //  });*/
     //  $show->field('base_price', __('Base price'));


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

       // $form->field('id', __('Id'));
        $form->text('title', __('Title'));
       // $label="E";
        $form->image('picture', __('Thumbnail'))->uniqueName();
       // $form->field('base_price', __('Base price'));
       // $form->number('base_price', __('Order'))->default(0);
$form->currency('base_price');

         //$form->field('base_price', __('Base price'));
 $form->editor('description');
// options 中参数会覆盖 extensions.ueditor.config 中参数
//$form->editor('description')->options(['initialFrameHeight' => 800]);

 //$form->editor('description', __('Article_content'));
       // $form->field('category_id', __('Category_id'));
       /* $form->field('picture', __('Thumbnail'))->image();
        
        $form->field('base_price', __('Base price'));

*/

        return $form;
    }
}
