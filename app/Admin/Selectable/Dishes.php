<?php

namespace App\Admin\Selectable;

use App\Models\Dish;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Dishes extends Selectable
{
    public $model = Dish::class;

    public function make()
    {
        $this->column('id');
        $this->column('title');
        /*$this->column('value');
        $this->column('units');
        $this->column('price');
*/
        $this->column('pictures')->image();
		$this->column('parameter');

        $this->filter(function (Filter $filter) {
            $filter->like('title');
        });
    }
    
    public static function display()
    {
        return function ($value) {

            // If `$value` is an array, it means it is used in the `collaborators` column, and the user’s name field separated by a semicolon `;` is displayed
            if (is_array($value)) {
                return implode(';', array_column($value,'name'));
            }

            // Otherwise it is used in the `author_id` column, which directly displays the user’s `name` field
            
            return optional($this->user)->name;
        };
    }
    

    }