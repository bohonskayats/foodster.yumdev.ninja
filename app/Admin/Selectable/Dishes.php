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

        $this->filter(function (Filter $filter) {
            $filter->like('title');
        });
    }
    

    }