<?php

namespace App\Admin\Selectable;

use App\Models\Parameter;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class Parameters extends Selectable
{
    public $model = Parameter::class;

    public function make()
    {
        $this->column('id');
        $this->column('title');
        $this->column('value');
        $this->column('units');
        $this->column('price');

        $this->filter(function (Filter $filter) {
            $filter->like('title');
        });
    }
    

    }